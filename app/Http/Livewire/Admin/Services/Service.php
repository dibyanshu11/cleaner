<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Services as ServicesModel;
use App\Models\ServicesItems;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Service extends Component
{
    use LivewireAlert;

    public $title, $status='1';

    public $itemTitle, $itemPrice, $itemDuration, $itemDescription, $itemStatus='1';


    public $serviceId, $serviceItemId;
    protected $listeners = ['confirmedDelete', 'confirmedItemDelete'];


    public function store()
    {
        $this->validate(
            [
                'title' => 'required|max:255',
                'status' => 'required',
            ]
        );

        $store = new ServicesModel;

        if($this->serviceId){
            $store->id = $this->serviceId;
            $store->exists = true;
        }
        $store->title = $this->title;
        $store->status = $this->status;
        $store->save();


        $this->resetInputFields();
        $this->emit('serviceFormClose');
        $this->alert('success', 'Service added successfully');
    }


    public function storeServiceItem($id){
        $this->resetInputFields();
        $this->resetItemInputFields();

        $this->serviceId = $id;

        $this->emit('serviceItemsForm');
    }

    public function storeItem()
    {
        $this->validate(
            [
                'itemTitle' => 'required|max:255',
                'itemPrice' => 'nullable|max:255',
                'itemDuration' => 'nullable|max:255',
                'itemDescription' => 'nullable|max:255',
                'itemStatus' => 'required',
            ],
            [
                'itemTitle.required' => 'The title field is required.',
                'itemStatus.required' => 'The status field is required.',
            ]
        );


        $store = new ServicesItems;

        if($this->serviceItemId){
            $store->id = $this->serviceItemId;
            $store->exists = true;
        }
        $store->services_id = $this->serviceId;
        $store->title = $this->itemTitle;
        $store->price = $this->itemPrice;
        $store->duration = $this->itemDuration;
        $store->description = $this->itemDescription;
        $store->status = $this->status;
        $store->save();


        $this->resetInputFields();
        $this->resetItemInputFields();
        $this->emit('serviceItemsFormClose');
        $this->alert('success', 'Item added successfully');
    }


    public function edit($id)
    {
        $this->resetInputFields();
        $service = ServicesModel::find($id);

        //...
        $this->serviceId = $service->id;
        $this->title = $service->title;
        $this->status = $service->status;
        
        $this->emit('serviceForm');
    }

    public function editItem($id)
    {
        $this->resetItemInputFields();
        $item = ServicesItems::find($id);

        //...
        $this->serviceItemId = $item->id;
        $this->serviceId = $item->services_id;
        $this->itemTitle = $item->title;
        $this->itemPrice = $item->price;
        $this->itemDuration = $item->duration;
        $this->itemDescription = $item->description;
        $this->itemStatus = $item->status;

        $this->emit('serviceItemsForm');
    }



    private function resetInputFields()
    {
        $this->title = '';
        $this->status = '1';
        $this->serviceId = '';
    }


    private function resetItemInputFields()
    {
        $this->itemTitle = '';
        $this->itemPrice = '1';
        $this->itemDuration = '';
        $this->itemDescription = '';
        $this->itemStatus = '';
        $this->serviceItemId = '';
    }

    public function deleteService($id)
    {
        $this->serviceId = $id;

        $this->alert('warning', 'Are you sure do want to delete?', [
            'toast' => false,
            'position' => 'center',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Delete it',
            'onConfirmed' => 'confirmedDelete',
            'timer' => null
        ]);
    }

    public function confirmedDelete()
    {
        $data = ServicesModel::findOrFail($this->serviceId);
        //...
        $data->delete();

        $this->resetInputFields();
        $this->alert('success', 'Item deleted successfully');
    }



    public function deleteItem($id)
    {
        $this->serviceItemId = $id;

        $this->alert('warning', 'Are you sure do want to delete?', [
            'toast' => false,
            'position' => 'center',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Delete it',
            'onConfirmed' => 'confirmedItemDelete',
            'timer' => null
        ]);
    }

    public function confirmedItemDelete()
    {
        $data = ServicesItems::findOrFail($this->serviceItemId);
        //...
        $data->delete();

        $this->resetItemInputFields();
        $this->alert('success', 'Item deleted successfully');
    }


    public function render()
    {

        $services = ServicesModel::with('items')->get();

        return view('livewire.admin.services.service', ['services'=>$services]);
    }
}
