<?php

namespace Webkul\Enclaves\Http\Controllers\Shop\Customer\Account;

use Webkul\Core\Traits\PDFHandler;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Enclaves\DataGrids\TransactionDataGrid;

class TransactionController extends AbstractController
{
    use PDFHandler;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(TransactionDataGrid::class)->toJson();
        }

        return view('shop::customers.account.transaction.index');
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $order = $this->orderRepository->findOneWhere([
            'customer_id' => self::customerId(),
            'id'          => $id,
        ]);

        return view('shop::customers.account.transaction.view', compact('order'));
    }

    /**
     * Print and download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function printInvoice($id)
    {
        $invoice = $this->invoiceRepository
                        ->where('id', $id)
                        ->whereHas('order', function ($query) {
                            $query->where('customer_id', self::customerId());
                        })
                        ->firstOrFail();

        return $this->downloadPDF(view('shop::customers.account.transaction.pdf', compact('invoice'))->render(),
            'invoice-' . $invoice->created_at->format('d-m-Y')
        );
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $customer = self::customer();

        /* find by order id in customer's order */
        $order = $customer->orders()->find($id);

        /* if order id not found then process should be aborted with 404 page */
        if (! $order) {
            abort(404);
        }

        $result = $this->orderRepository->cancel($order);

        if ($result) {
            session()->flash('success', trans('shop::app.customers.account.orders.view.cancel-success', ['name' => trans('admin::app.customers.account.orders.order')]));
        } else {
            session()->flash('error', trans('shop::app.customers.account.orders.view.cancel-error', ['name' => trans('admin::app.customers.account.orders.order')]));
        }

        return redirect()->back();
    }
}
