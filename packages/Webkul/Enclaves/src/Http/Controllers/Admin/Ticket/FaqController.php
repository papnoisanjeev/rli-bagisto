<?php

namespace Webkul\Enclaves\Http\Controllers\Admin\Ticket;

use Illuminate\Http\JsonResponse;
use Webkul\Enclaves\DataGrids\FaqDataGrid;
use Webkul\Enclaves\Repositories\FaqRepository;
use Webkul\Enclaves\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;

class FaqController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected FaqRepository $faqRepository
    ) {
    }

    /**
     * Edit the theme
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(FaqDataGrid::class)->toJson();
        }

        return view('enclaves::admin.inquiries.faq.index');
    }

    /**
     * Store Inquiries
     */
    public function store() 
    {
        $this->validate(request(), [
            'question'    => 'required',
            'answer'      => 'required',
        ]);

        $data = request()->only([
            'question',
            'answer',
            'status',
            'status_switch',
        ]);

        if(request()->has('status_switch')) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        $this->faqRepository->create($data);

        session()->flash('success', trans('enclaves::app.admin.inquiries.faq.form.create.create-success'));

        return redirect()->back();
    }

    /**
     * Update Inquiries
     */
    public function update() 
    {
        try {
            $this->validate(request(), [
                'id'          => 'required',
                'question'    => 'required',
                'answer'      => 'required',
            ]);
    
            $data = request()->only([
                'id',
                'question',
                'answer',
                'status',
                'status_switch',
            ]);
            
            if(request()->has('status_switch')) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }

            $this->faqRepository->update($data, $data['id']);
    
            return response()->json([
                'message' => trans('enclaves::app.admin.inquiries.faq.form.create.update-success'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->faqRepository->delete($id);

            return response()->json([
                'message' => trans('enclaves::app.admin.inquiries.faq.form.create.delete-success'),
            ]);

        } catch (\Throwable $th) {
        }

        return response()->json([
            'message' => trans('enclaves::app.admin.inquiries.faq.form.create.delete-failed'),
        ], 500);
    }

    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            $this->faqRepository->delete($index);
        }

        return new JsonResponse([
            'message' => trans('enclaves::app.admin.inquiries.faq.form.create.delete-success'),
        ]);
    }
}
