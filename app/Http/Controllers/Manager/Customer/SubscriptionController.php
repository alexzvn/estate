<?php

namespace App\Http\Controllers\Manager\Customer;

use App\Repository\Subscription;
use App\Http\Controllers\Manager\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function deleteMany(Request $request)
    {
        $this->authorize('manager.subscription.delete');

        Subscription::whereIn(id, $request->subscriptions ?? [])->delete();

        return back()->with('success', 'Xóa thành công');
    }

    public function lockToggle(string $id)
    {
        $this->authorize('manager.subscription.lock');

        $sub = Subscription::findOrFail($id);

        $sub->forceFill([
            'lock' => ! $sub->lock
        ])->save();
    }
}
