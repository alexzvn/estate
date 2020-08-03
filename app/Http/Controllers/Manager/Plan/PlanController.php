<?php

namespace App\Http\Controllers\Manager\Plan;

use App\Enums\PostType;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Plan\StorePlan;
use App\Http\Requests\Manager\Plan\UpdatePlan;
use App\Repository\Category;
use App\Repository\Location\Province;
use App\Repository\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $this->authorize('manager.plan.view');

        return view('dashboard.plan.index', [
            'plans' => Plan::all(),
            'postTypes' => PostType::getValues(),
            'provinces' => Province::active()->get(),
            'categories' => Category::parentOnly()->get(),
        ]);
    }

    public function view(string $id, Plan $plan)
    {
        $this->authorize('manager.plan.view');

        

        return view('dashboard.plan.edit', [
            'plan' => $plan->with(['provinces', 'categories'])->findOrFail($id),
            'postTypes' => PostType::getValues(),
            'provinces' => Province::active()->get(),
            'categories' => Category::parentOnly()->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.plan.create');
    }

    public function store(StorePlan $request, Plan $plan)
    {
        $plan->fill([
            'name' => $request->name,
            'price' => (float) str_replace(',', '', $request->price ?? ''),
            'types' => $request->post_type ?? []
        ])->save();

        $this->savePlansRelation($plan, $request);

        return redirect(route('manager.plan'))->with('success', 'Tạo mới thành công');
    }

    public function update(UpdatePlan $request)
    {
        $plan = $request->getPlan();

        $plan->fill([
            'name' => $request->name,
            'price' => (float) str_replace(',', '', $request->price ?? ''),
            'types' => $request->post_type ?? []
        ])->save();


        $this->savePlansRelation($plan, $request);

        return redirect(route('manager.plan'))->with('success', 'Cập nhật thành công');
    }

    public function delete(string $id)
    {
        $this->authorize('manager.plan.delete');

        if ($plan = Plan::findOrFail($id)) {
            $plan->delete();
        }

        return redirect(route('manager.plan'))->with('success', 'Xóa thành công gói đăng ký');
    }

    private function savePlansRelation($model, Request $request)
    {
        $model->categories()->sync($request->categories ?? []);
        $model->provinces()->sync($request->provinces ?? []);
    }
}
