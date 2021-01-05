<div>
    <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
        <div>
            <div class="flex flex-wrap justify-between item-s-center">
                <div class="flex-grow order-3 w-full mr-3 md:w-auto md:order-1">
                    <input type="search" placeholder="Search files and folders" name=""
                        class="w-full h-12 px-3 border-2 rounded-lg hx-12" />
                </div>
                <div class="order-2 mb-2 md:mb-0">
                    <button wire:click="$set('creatingNewFolder', true)'"
                        class="h-12 px-6 mr-2 bg-gray-200 rounded-lg">New folder</button>
                    <button wire:click="$set('showingFileUploadForm', true)"
                        class="h-12 px-6 font-bold text-white bg-blue-600 rounded-lg">Upload
                        files</button>
                </div>
            </div>
        </div>


        <div class="mt-4 border-2 border-gray-200 rounded-lg">
            <div class="px-3 py-2">
                <div class="flex items-center">
                    @foreach($ancestors as $ancestor)
                    <a href="{{ route('files', ['uuid' => $ancestor->uuid]) }}"
                        class="font-semibold text-gray-400">{{ $ancestor->objectable->name }}</a>
                    @if(!$loop->last)
                    <svg class="w-4 h-4 mx-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    @endif
                    @endforeach
                </div>
            </div>

            <table class="w-full table-fixed">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left">Name</th>
                        <th class="w-2/12 px-3 py-2 text-left">Size</th>
                        <th class="w-2/12 px-3 py-2 text-left">Created</th>
                        <th class="w-2/12 p-2"></th>
                    </tr>
                </thead>
                <tbody>

                    @if($creatingNewFolder)
                    <tr class="border-b-2 border-gray-100">
                        <td class="p-3">
                            <form action="" wire:submit.prevent="createFolder" class="flex items-center">
                                <input type="text" name="" id=""
                                    class="w-full h-10 px-3 mr-2 border-2 border-gray-200 rounded-lg"
                                    wire:model="newFolderState.name">
                                <button type="submit"
                                    class="h-10 px-6 mr-2 text-white bg-blue-700 rounded-lg">Create</button>
                                <button wire:click="$set('creatingNewFolder', false)'"
                                    class="h-10 px-6 mr-2 bg-gray-200 rounded-lg ">Cancel</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                    @foreach($object->children as $child)
                    <tr class="border-b-2 border-gray-100 hover:bg-gray-100">
                        <td class="flex items-center px-3 py-2">
                            @if($child->objectable_type == 'folder')
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                </path>
                            </svg>
                            @endif

                            @if($child->objectable_type == 'file')
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            @endif
                            @if($renamingObject === $child->id)
                            <form wire:submit.prevent="renameObject" class="flex items-center flex-grow">
                                <input type="text" name="" id=""
                                    class="w-full h-10 px-3 mx-2 border-2 border-gray-200 rounded-lg"
                                    wire:model.lazy="renamingObjectState.name">
                                <button type="submit"
                                    class="h-10 px-6 mr-2 text-white bg-blue-700 rounded-lg">Update</button>
                                <button wire:click="$set('renamingObject', null)'"
                                    class="h-10 px-6 mr-2 bg-gray-200 rounded-lg ">Cancel</button>
                            </form>
                            @else
                            @if($child->objectable_type === 'folder')
                            <a href="{{ route('files', ['uuid' => $child->uuid]) }}"
                                class="flex-grow p-2 font-bold text-blue-700">
                                {{ optional($child->objectable)->name }}
                            </a>
                            @else
                            <a href="#" class="flex-grow p-2 font-bold text-gray-700">
                                {{  optional($child->objectable)->name }}
                            </a>
                            @endif
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            @if($child->objectable_type == 'file')
                            {{ $child->objectable->sizeForHumans() }}
                            @else
                            &mdash;
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            created at
                        </td>
                        <td class="px-3 py-2 ">
                            <div class="flex items-center justify-end">
                                <ul class="flex items-center">
                                    <li class="mr-4">
                                        <button class="font-bold text-gray-400"
                                            wire:click="$set('renamingObject', {{ $child->id }})">Rename</button>
                                    </li>
                                    <li>
                                        <button wire:click="$set('confirmingObjectDeletion', {{ $child->id }})"
                                            class="font-bold text-red-400">Delete</button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($object->children->count() == 0)
            <div class="p-3 text-gray-400">
                This folder is empty
            </div>
            @endif
        </div>
    </div>
    <x-jet-modal wire:model="showingFileUploadForm">
        <div wire:ignore class="m-3 border-2 border-dashed" x-data="{
            initFilepond(){
                const pond = FilePond.create(this.$refs.filepond, {

                    onprocessfile: (error, file) =>{
                      pond.removeFile(file.id)
                      if(pond.getFiles().length === 0)
                      {
                          @this.set('showingFileUploadForm', false)
                      }
                    },
                    allowRevert: false,
                    server:{
                        process: (fieldName, file, metdata, load, error, progress, abort, transfer, options) => {
                            @this.upload('upload', file, load, error, progress)
                        }
                    }
                })
            }
        }" x-init="initFilepond">
            <div>
                <input type="file" x-ref="filepond" multiple />
            </div>
        </div>
    </x-jet-modal>

    <x-jet-dialog-modal wire:model="confirmingObjectDeletion">
        <x-slot name="title">
            {{ __('Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete?') }}

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingObjectDeletion', null)" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteObject" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
