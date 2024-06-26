<?php

namespace Webkul\Enclaves\Http\Controllers\Admin\Ticket;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Enclaves\DataGrids\TicketsDataGrid;
use Webkul\Enclaves\Http\Controllers\Controller;
use Webkul\Enclaves\Repositories\TicketsRepository;
use Webkul\Enclaves\Repositories\TicketStatusRepository;

class TicketsController extends Controller
{
   /**
     * Create a new controller instance.
     */
    public function __construct(
        protected TicketStatusRepository $ticketStatusRepository,
        protected TicketsRepository $ticketsRepository,
        protected CustomerRepository $customerRepository,
    ) {
    }

    /**
     * view tickets
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        if (request()->ajax()) {
            return app(TicketsDataGrid::class)->toJson();
        }

        return view('enclaves::admin.inquiries.tickets.index');
    }

    /**
     * view Inquiries
     *
     * @return \Illuminate\View\View
     */
    public function view(int $id)
    {
        $ticket = $this->ticketsRepository->with(['files', 'reasons', 'status'])->findOrFail($id);

        $customer = $this->customerRepository->findOrFail($ticket->customer_id);

        return view('enclaves::admin.inquiries.tickets.view', compact('ticket', 'customer'));
    }

    /**
     * Store Inquiries
     */
    public function store()
    {
        $this->validate(request(), [
            'customer_id'      => 'required',
            'ticket_reason_id' => 'required',
            'ticket_status_id' => 'required',
            'comment'          => 'required',
        ]);

        $request = request()->only([
            'customer_id',
            'ticket_reason_id',
            'ticket_status_id',
            'comment',
            'file',
        ]);

        $ticket = $this->ticketsRepository->create($request);

        $this->ticketsRepository->uploadMultipleImages($ticket);

        session()->flash('success', trans('enclaves::app.admin.inquiries.tickets.form.create.create-success'));

        return redirect()->back();
    }

    /**
     * Update Inquiries
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'customer_id'      => 'required',
            'ticket_reason_id' => 'required',
            'ticket_status_id' => 'required',
            'comment'          => 'required',
        ]);

        $request = request()->only([
            'customer_id',
            'ticket_reason_id',
            'ticket_status_id',
            'comment',
        ]);

        $ticket = $this->ticketsRepository->update($request, $id);

        if (request()->hasFile('file')) {
            $this->ticketsRepository->uploadMultipleImages($ticket);
        }

        session()->flash('success', trans('enclaves::app.admin.inquiries.tickets.form.edit.update-success'));

        return redirect()->back();
    }

    /**
     * Delete Inquiries
     */
    public function destroy(int $id)
    {
        try {
            $dir = 'tickets/' . $id;

            if (Storage::has($dir)) {
                Storage::delete($dir);
            }

            $this->ticketsRepository->delete($id);

            session()->flash('success', trans('enclaves::app.admin.inquiries.tickets.form.edit.delete-success'));

            return redirect()->route('enclaves.admin.inquiries.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * mass Delete Inquiries
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $ids = $massDestroyRequest->input('indices');

        try {
            foreach ($ids as $id) {
                $product = $this->ticketsRepository->find($id);

                if (isset($product)) {
                    $dir = 'tickets/' . $id;

                    if (Storage::has($dir)) {
                        Storage::delete($dir);
                    }

                    $this->ticketsRepository->delete($id);
                }
            }

            return new JsonResponse([
                'message' => trans('enclaves::app.admin.inquiries.tickets.form.edit.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}