<?php

namespace App\Http\Controllers\Manager\Sms;

use Alexzvn\Speedsms\Speedsms;
use App\Http\Controllers\Manager\Controller;
use App\Models\SmsHistory;
use App\Models\SmsTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use function Illuminate\Events\queueable;

class SmsController extends Controller
{
    protected Speedsms $smsVendor;

    /**
     * Sender using when send sms
     */
    protected string $sender;

    public function __construct() {
        parent::__construct();

        $this->smsVendor = new Speedsms(config('services.speedsms.key'));

        $this->sender = collect(config('services.speedsms.devices'))->random();
    }

    public function index(Request $request)
    {
        if ($recipients = $request->recipients) {
            $recipients = is_array($recipients) ? $recipients : [$recipients];
        }

        return view('dashboard.sms.index', [
            'templates' => SmsTemplate::all(),
            'recipients' => $recipients ?? []
        ]);
    }

    public function create()
    {
        return view('dashboard.sms.create');
    }

    public function store(Request $request, SmsTemplate $template)
    {
        $request->validate([
            'name' => 'required|string',
            'message' => 'required|string'
        ]);

        $template->fill([
            'name' => $request->name,
            'content' => $request->message
        ]);

        $request->user()->smsTemplates()->save($template);

        return redirect(route('manager.sms.template'))->with('success', 'Tạo mẫu tin nhắn mới thành công');
    }

    public function edit($template)
    {
        $template = SmsTemplate::findOrFail($template);

        return view('dashboard.sms.edit', compact('template'));
    }

    public function update(Request $request, $template)
    {
        $template = SmsTemplate::findOrFail($template);

        $request->validate([
            'name' => 'required|string',
            'message' => 'required|string'
        ]);

        $template->fill([
            'name' => $request->name,
            'content' => $request->message
        ])->save();

        return back()->with('success', 'Sửa mẫu sms thành công');
    }

    public function delete($template)
    {
        $template = SmsTemplate::findOrFail($template);

        $template->delete();

        return redirect(route('manager.sms.template'))->with('success', 'Đã xóa mẫu này');
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipients.*' => 'required|alpha_num|min:10|max:10|distinct',
            'message' => 'required|string|max:160',
        ]);

        $message = $request->message;

        $sms = collect($request->recipients)->map(function (string $recipient) use ($message) {
            $recipient = trim($recipient);

            return tap(new SmsHistory)
                ->fill(compact('recipient', 'message'))
                ->forceFill(['user_id' => auth()->id()]);
        });

        $this->sendMany($sms, $message);

        dispatch(queueable(function () use ($sms) {
            $sms->each(fn(SmsHistory $sms) => $sms->save());
        }));

        return back()->with('success', 'Đang gửi '. $sms->count() . ' tin nhắn');
    }

    public function sendMany(Collection $sms, string $content)
    {
        return $this->smsVendor->sendListSms(
            $sms->keyBy('recipient')->keys()->toArray(),
            $content,
            Speedsms::SMS_GATEWAY,
            $this->sender
        );
    }
}
