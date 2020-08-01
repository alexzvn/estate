<?php

namespace App\Http\Controllers\Manager\Customer;

use App\Repository\Subscription;
use App\Http\Controllers\Manager\Controller;

class SubscriptionController extends Controller
{
    public function delete(string $id)
    {
        $this->authorize('manager.subscription.delete');

        Subscription::findOrFail($id)->delete();

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
