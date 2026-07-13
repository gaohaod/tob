@section('title', 'Avatar Editor')

<div class="modal fade" id="ErrorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span id="contnt"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Okay, close this modal</button>
      </div>
    </div>
  </div>
</div>

<x-app-layout>
    <link href="/BodyColors.css" rel="stylesheet">
    @include('avatar.bodycolors_modal')
   
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <x-card :title="'Character Preview'">
                    <img src="/cdn/users/{{ Auth::id() }}?t={{ time() }}" alt="Your Character" id="avatar-preview" width="100%" class="mb-3">
                    <button class="btn btn-success w-100" id="regen-button" onclick="Regenerate()">
                        <i class="far fa-sync mr-2" id="spinnyfuny"></i>
                        <span id="regen-text">Regenerate</span>
                    </button>
                </x-card>
            </div>
            <div class="col">
                <x-card :title="'Editor'">
                    <ul class="nav nav-tabs" id="avatarEditorTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="hats-tab" data-toggle="tab" data-target="#hats" type="button" role="tab" aria-controls="hats" aria-selected="true">Hats</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="shirts-tab" data-toggle="tab" data-target="#shirts" type="button" role="tab" aria-controls="shirts" aria-selected="false">Shirts</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="pants-tab" data-toggle="tab" data-target="#pants" type="button" role="tab" aria-controls="pants" aria-selected="false">Pants</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="gears-tab" data-toggle="tab" data-target="#gears" type="button" role="tab" aria-controls="gears" aria-selected="false">Gears</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="heads-tab" data-toggle="tab" data-target="#heads" type="button" role="tab" aria-controls="heads" aria-selected="false">Heads</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="tshirts-tab" data-toggle="tab" data-target="#tshirts" type="button" role="tab" aria-controls="tshirts" aria-selected="false">T-Shirts</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="faces-tab" data-toggle="tab" data-target="#faces" type="button" role="tab" aria-controls="faces" aria-selected="false">Faces</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bodycolors-tab" data-toggle="tab" data-target="#bodycolors" type="button" role="tab" aria-controls="bodycolors" aria-selected="false">Bodycolors</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="avatarEditorTabContent">

                        <div class="tab-pane fade show active" id="hats" role="tabpanel" aria-labelledby="hats-tab">
                            @if ($hats->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You don't have anything here.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($hats as $hat)
                                        <div class="col-md-2 mb-4" id="item-{{ $hat->asset->id }}">
                                                @if ($hat->asset->type == "tshirt" || $hat->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $hat->asset->id - 1 }}&t={{ time() }}" alt="{{ $hat->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $hat->asset->id }}?t={{ time() }}" alt="{{ $hat->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $hat->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $hat->asset->id }})" id="wear-btn-{{ $hat->asset->id }}" data-wearing-item="true" data-wearing="{{ $hat->wearing }}" >{{ $hat->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $hats->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="shirts" role="tabpanel" aria-labelledby="shirts-tab">
                            @if ($shirts->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You don't have anything here.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($shirts as $shirt)
                                        <div class="col-md-2 mb-4" id="item-{{ $shirt->asset->id }}">
                                                @if ($shirt->asset->type == "tshirt" || $shirt->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $shirt->asset->id - 1 }}&t={{ time() }}" alt="{{ $shirt->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $shirt->asset->id }}?t={{ time() }}" alt="{{ $shirt->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $shirt->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $shirt->asset->id }})" id="wear-btn-{{ $shirt->asset->id }}" data-wearing-item="true" data-wearing="{{ $shirt->wearing }}" >{{ $shirt->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $shirts->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="pants" role="tabpanel" aria-labelledby="pants-tab">
                            @if ($pants->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You don't have anything here.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($pants as $pant)
                                        <div class="col-md-2 mb-4" id="item-{{ $pant->asset->id }}">
                                                @if ($pant->asset->type == "tshirt" || $pant->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $pant->asset->id - 1 }}&t={{ time() }}" alt="{{ $pant->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $pant->asset->id }}?t={{ time() }}" alt="{{ $pant->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $pant->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $pant->asset->id }})" id="wear-btn-{{ $pant->asset->id }}" data-wearing-item="true" data-wearing="{{ $pant->wearing }}" >{{ $pant->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $pants->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="gears" role="tabpanel" aria-labelledby="gears-tab">
                            @if ($gears->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You don't have anything here.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($gears as $gear)
                                        <div class="col-md-2 mb-4" id="item-{{ $gear->asset->id }}">
                                                @if ($gear->asset->type == "tshirt" || $gear->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $gear->asset->id - 1 }}&t={{ time() }}" alt="{{ $gear->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $gear->asset->id }}?t={{ time() }}" alt="{{ $gear->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $gear->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $gear->asset->id }})" id="wear-btn-{{ $gear->asset->id }}" data-wearing-item="true" data-wearing="{{ $gear->wearing }}" >{{ $gear->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $gears->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="heads" role="tabpanel" aria-labelledby="heads-tab">
                            @if ($heads->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You don't have anything here.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($heads as $head)
                                        <div class="col-md-2 mb-4" id="item-{{ $head->asset->id }}">
                                                @if ($head->asset->type == "tshirt" || $head->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $head->asset->id - 1 }}&t={{ time() }}" alt="{{ $head->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $head->asset->id }}?t={{ time() }}" alt="{{ $head->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $head->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $head->asset->id }})" id="wear-btn-{{ $head->asset->id }}" data-wearing-item="true" data-wearing="{{ $head->wearing }}" >{{ $head->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $heads->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="tshirts" role="tabpanel" aria-labelledby="tshirts-tab">
                            @if ($tshirts->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You don't have anything here.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($tshirts as $tshirt)
                                        <div class="col-md-2 mb-4" id="item-{{ $tshirt->asset->id }}">
                                                @if ($tshirt->asset->type == "tshirt" || $tshirt->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $tshirt->asset->id - 1 }}&t={{ time() }}" alt="{{ $tshirt->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $tshirt->asset->id }}?t={{ time() }}" alt="{{ $tshirt->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $tshirt->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $tshirt->asset->id }})" id="wear-btn-{{ $tshirt->asset->id }}" data-wearing-item="true" data-wearing="{{ $tshirt->wearing }}" >{{ $tshirt->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $tshirts->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="faces" role="tabpanel" aria-labelledby="faces-tab">
                            @if ($faces->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You don't have anything here.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($faces as $face)
                                        <div class="col-md-2 mb-4" id="item-{{ $face->asset->id }}">
                                                @if ($face->asset->type == "tshirt" || $face->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $face->asset->id - 1 }}&t={{ time() }}" alt="{{ $face->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $face->asset->id }}?t={{ time() }}" alt="{{ $face->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $face->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $face->asset->id }})" id="wear-btn-{{ $face->asset->id }}" data-wearing-item="true" data-wearing="{{ $face->wearing }}" >{{ $face->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $tshirts->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="bodycolors" role="tabpanel" aria-labelledby="bodycolors-tab">
                            <div class="my-4 d-flex justify-content-center">
                                <div style="height: 240px; width: 194px; text-align: center;">
                                    <div style="position: relative; margin: 11px 4px; height: 100%;">
                                        <div style="position: absolute; left: 72px; top: 0px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->head }}" id="head" onclick="SetBodyPart('head')" data-toggle="modal" data-target="#colorPickerModal" style="height: 44px; width: 44px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 0px; top: 52px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->larm }}" id="larm" onclick="SetBodyPart('larm')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 40px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 48px; top: 52px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->torso }}" id="torso" onclick="SetBodyPart('torso')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 88px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 144px; top: 52px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->rarm }}" id="rarm" onclick="SetBodyPart('rarm')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 40px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 48px; top: 146px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->lleg }}" id="lleg" onclick="SetBodyPart('lleg')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 40px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 96px; top: 146px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->rleg }}" id="rleg" onclick="SetBodyPart('rleg')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 40px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <script src="/functions.js"></script>
    <script>
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        window.regenerating = false;

        async function Regenerate() {
            let spinnyfuny = document.getElementById('spinnyfuny');
            let regenbutton = document.getElementById('regen-button');
            let regentext = document.getElementById('regen-text');
            let avatar = document.getElementById('avatar-preview');

            if (window.regenerating == true) {
                return;
            }

            window.regenerating = true;

            regenbutton.disabled = true;
            regentext.innerHTML = "Regenerating";
            spinnyfuny.classList.add('fa-spin');

            avatar.src = "/images/loader2.gif";

            await fetch('/test/{{ Auth::id() }}');

            regenbutton.disabled = false;
            regentext.innerHTML = "Regenerate";
            spinnyfuny.classList.remove('fa-spin');

            avatar.src = "/cdn/users/{{ Auth::id() }}?t=" + Date.now();

            console.log("Regeneration complete");

            window.regenerating = false;
        }

        async function wear(id, type) {
            try {
                const allButtons = document.querySelectorAll('[id^="wear-btn-"]');
                allButtons.forEach(button => button.disabled = true);

                const wornItems = document.querySelectorAll('[data-wearing="1"]');
                const wornCounts = {
                    Hats: 0,
                    Shirts: 0,
                    Pants: 0,
                    'T-Shirts': 0,
                    Gears: 0,
                    Heads: 0,
                    Faces: 0
                };

                wornItems.forEach(item => {
                    const itemType = item.getAttribute('data-type');
                    if (wornCounts[itemType] !== undefined) {
                        wornCounts[itemType]++;
                    }
                });

                const limits = {
                    Hats: 3,
                    Shirts: 1,
                    Pants: 1,
                    'T-Shirts': 1,
                    Gears: 3,
                    Heads: 1,
                    Faces: 1
                };

                const button = document.getElementById(`wear-btn-${id}`);
                const currentlyWearing = button.getAttribute('data-wearing') === '1';
                if (!currentlyWearing && wornCounts[type] >= limits[type]) {
                    alert(`You can only wear a maximum of ${limits[type]} ${type}(s).`);
                    allButtons.forEach(button => button.disabled = false);
                    return;
                }

                const response = await fetch(`/app/wear-item/${id}`);

                if (response.status === 400) {
                    document.getElementById('contnt').innerHTML = "You can't wear any more of these items!";
                    $('#ErrorModal').modal('toggle');
                }

                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }

                if (button) {
                    button.textContent = currentlyWearing ? 'Wear' : 'Unwear';
                    button.setAttribute('data-wearing', currentlyWearing ? '0' : '1');

                    await Regenerate();

                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error.message);
            } finally {
                const allButtons = document.querySelectorAll('[id^="wear-btn-"]');
                allButtons.forEach(button => button.disabled = false);
            }
        }


        function SetBodyPart(part) {
            window.bPart = part; 
        }

        async function ChangeBodyColor(part, code) {
            console.log('[BodyColors] - Part : ' + part + ' Color : ' + code);
    
            let element = document.getElementById(part);
    
            element.classList.forEach(className => {
                if (className.startsWith('brick-color-')) {
                    element.classList.remove(className);
                }
            });

            await fetch('/app/change-body-color/' + code + '/' + part);

            element.classList.add('brick-color-' + code);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const activeTab = localStorage.getItem('activeTab');
            
            if (activeTab) {
                const tabElement = document.querySelector(`a[href="${activeTab}"]`);
                if (tabElement) {
                    const tab = new bootstrap.Tab(tabElement);
                    tab.show();
                }
            }

            const tabLinks = document.querySelectorAll('a[data-toggle="tab"], a[data-bs-toggle="tab"]');
            tabLinks.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (event) {
                    const activeTabHref = event.target.getAttribute('href');
                    localStorage.setItem('activeTab', activeTabHref);
                });
            });
        });
    </script>
</x-app-layout>
