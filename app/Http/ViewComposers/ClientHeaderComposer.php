<?php


namespace App\Http\ViewComposers;

use App\Models\Contact;
use Illuminate\View\View;
use App\Models\Client;

class ClientHeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $clients = Client::findOrFail($view->getData()['client']['id']);

        $contact_info = $clients->contacts()->first();
        /**
         * [User assigned the client]
         * @var contact $clients
         */
        $contact = $clients->user;

        $view->with('contact', $contact)->with('contact_info', $contact_info)->with($clients);
    }
}
