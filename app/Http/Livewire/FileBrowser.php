<?php

namespace App\Http\Livewire;

use App\Models\Obj;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileBrowser extends Component
{
    use WithFileUploads;
    
    public $query;
    public $upload;
    public $object;
    public $ancestors;
    public $showingFileUploadForm = false;
    public $creatingNewFolder = false;
    public $renamingObject;
    public $confirmingObjectDeletion;

    public $newFolderState = [
        'name' => ''
    ];
    
    public $renamingObjectState = [
        'name' => ''
    ];

    public function deleteObject()
    {
        Obj::forCurrentTeam()->find($this->confirmingObjectDeletion)->delete();
        $this->object = $this->object->fresh();
        $this->confirmingObjectDeletion = null;
    }

    public function updatedUpload($upload)
    {
       $object = $this->currentTeam->objects()->make(['parent_id' => $this->object->id]);
       $object->objectable()->associate(
           $this->currentTeam->files()->create([
                'name' => $upload->getClientOriginalName(),
                'size' => $upload->getSize(),
                'path' => $upload->storePublicly(
                    'files', [
                        'disk' => 'local'
                    ]
                )
           ])
       );

       $object->save();
       $this->object = $this->object->fresh();
        // $upload->storePublicly('files', ['disk' => 'local']);
    }

    public function renameObject()
    {
        $this->validate([
            'renamingObjectState.name' => 'required|max:255'
        ]);

        Obj::forCurrentTeam()->find($this->renamingObject)->objectable->update($this->renamingObjectState);

        $this->object = $this->object->fresh();
        $this->renamingObject = null;
        $this->renamingObjectState = [
            'name' => ''
        ];
    }

    public function updatingRenamingObject($id)
    {
     
        if($id === null)
        {
            return;
        }

        if($object = Obj::forCurrentTeam()->find($id))
        {
            $this->renamingObjectState = [
                'name' => $object->objectable->name
            ];
        }
    }

    public function createFolder()
    {
        $this->validate([
            'newFolderState.name' => 'required|max:255'
        ]);

        $object = $this->currentTeam->objects()->make(['parent_id' => $this->object->id]);
        $object->objectable()->associate($this->currentTeam->folders()->create($this->newFolderState));
        $object->save();

        $this->object = $this->object->fresh();
        $this->creatingNewFolder = false;
        $this->newFolderState = [
            'name' => ''
        ];
    

    }

    public function getCurrentTeamProperty()
    {
        return auth()->user()->currentTeam;
    }

    public function render()
    {
        return view('livewire.file-browser');
    }
}
