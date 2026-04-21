@extends('layouts.public')

@section('title', 'Immigration & Study Permit Information')

@section('content')

{{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
<div class="bg-gradient-to-r from-red-900 to-red-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-widest text-red-300 font-semibold mb-2">Information Hub</p>
                <h1 class="text-4xl md:text-5xl font-bold">Immigration & Study Permit</h1>
                <p class="mt-3 text-lg opacity-90 max-w-2xl">Everything international students need — visa requirements, downloadable forms, office locations, and step-by-step guidance.</p>
                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="#requirements" class="bg-white text-red-800 px-5 py-2.5 rounded-lg font-semibold hover:bg-gray-100 transition text-sm">View Requirements</a>
                    <a href="#forms" class="border-2 border-white text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition text-sm">Download Forms</a>
                </div>
            </div>
            <a href="{{ url('/information-hub') }}" class="text-white hover:underline text-sm flex items-center gap-2 shrink-0">
                <i class="fas fa-arrow-left"></i> Back to Information Hub
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">

    {{-- ── Section 1: Study Permit Overview ────────────────────────────────── --}}
    <section>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <i class="fas fa-passport text-red-800"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Study Permit Overview</h2>
                </div>
                <div class="space-y-4 text-gray-700">
                    <div>
                        <h3 class="font-semibold text-gray-900">What is a study permit?</h3>
                        <p class="mt-1 text-sm leading-relaxed">A study permit (student visa) is an official document that authorises a non-citizen to study at an accredited institution in Botswana. It is issued by the Department of Immigration and Citizenship Services.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Who needs it?</h3>
                        <p class="mt-1 text-sm leading-relaxed">All international students — students who are not citizens or permanent residents of Botswana — are required to hold a valid study permit for the duration of their studies at the University of Botswana.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">When should I apply?</h3>
                        <p class="mt-1 text-sm leading-relaxed">Apply as early as possible — ideally <strong>3–6 months before</strong> your intended start date. Processing times vary and delays may affect registration. If already in Botswana on a visitor's permit, you must change status before it expires.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-exclamation-triangle text-amber-600"></i>
                        <h3 class="font-semibold text-amber-800">Important Deadlines</h3>
                    </div>
                    @php $highPriority = $requirements->where('priority', 1); @endphp
                    @if($highPriority->count() > 0)
                        <ul class="space-y-3">
                            @foreach($highPriority->take(3) as $item)
                            <li class="text-sm text-amber-800">
                                <span class="font-semibold">{{ $item->title }}</span>
                                @if($item->deadline)
                                <span class="block text-amber-700 mt-0.5"><i class="fas fa-calendar-alt mr-1"></i>{{ $item->deadline->format('d M Y') }}</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-amber-700">Check with the International Students Office for current deadlines.</p>
                    @endif
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        <h3 class="font-semibold text-blue-800">Need help?</h3>
                    </div>
                    <p class="text-sm text-blue-700">Contact the UB International Students Office for personalised guidance on your permit application.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Section 2: Requirements Checklist ───────────────────────────────── --}}
    <section id="requirements">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-clipboard-check text-red-800"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Requirements for a Study Permit</h2>
        </div>

        {{-- Static core checklist --}}
        <div class="bg-white rounded-2xl shadow p-8 mb-6">
            <p class="text-sm text-gray-600 mb-6">The following documents are typically required. Always confirm with the Department of Immigration for the most current list.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @php
                $coreRequirements = [
                    ['icon' => 'fa-id-card', 'text' => 'Valid passport (at least 6 months validity beyond intended stay)'],
                    ['icon' => 'fa-file-alt', 'text' => 'Original admission/acceptance letter from the University of Botswana'],
                    ['icon' => 'fa-home', 'text' => 'Proof of accommodation (lease agreement or booking confirmation)'],
                    ['icon' => 'fa-money-bill-wave', 'text' => 'Proof of sufficient financial support (bank statements / sponsorship letter)'],
                    ['icon' => 'fa-camera', 'text' => 'Recent passport-size photographs (as specified by the application form)'],
                    ['icon' => 'fa-file-signature', 'text' => 'Completed and signed study permit application form'],
                    ['icon' => 'fa-stethoscope', 'text' => 'Medical clearance certificate (if required by your nationality)'],
                    ['icon' => 'fa-certificate', 'text' => 'Certified copies of previous academic certificates/transcripts'],
                ];
                @endphp
                @foreach($coreRequirements as $req)
                <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fas {{ $req['icon'] }} text-green-700 text-sm"></i>
                    </div>
                    <p class="text-sm text-gray-800">{{ $req['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Dynamic requirements from DB --}}
        @if($requirements->count() > 0)
            {{-- Category filter --}}
            @if($categories->count() > 1)
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('information.immigration') }}" class="px-4 py-2 rounded-full text-sm font-medium {{ !request('category') ? 'bg-red-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">All</a>
                @foreach($categories as $cat)
                <a href="{{ route('information.immigration', ['category' => $cat]) }}" class="px-4 py-2 rounded-full text-sm font-medium {{ request('category') == $cat ? 'bg-red-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">{{ ucfirst($cat) }}</a>
                @endforeach
            </div>
            @endif

            <div class="space-y-4">
                @foreach($requirements as $req)
                <div class="bg-white rounded-2xl shadow border {{ $req->priority == 1 ? 'border-l-4 border-l-red-600' : 'border-gray-100' }} p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $req->title }}</h3>
                            @if($req->description)
                            <p class="text-gray-600 mt-2 text-sm">{{ $req->description }}</p>
                            @endif
                        </div>
                        <span class="{{ $req->priority_badge }} px-3 py-1 rounded-full text-xs font-semibold shrink-0">
                            @if($req->priority == 1) High Priority
                            @elseif($req->priority == 2) Medium
                            @else Standard
                            @endif
                        </span>
                    </div>

                    @php $reqDocs = is_array($req->required_documents) ? $req->required_documents : []; @endphp
                    @if(count($reqDocs) > 0)
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Required documents:</h4>
                        <ul class="space-y-1">
                            @foreach($reqDocs as $doc)
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-check-circle text-green-600 text-xs"></i>{{ $doc }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($req->process_steps)
                    <div class="mt-4 bg-gray-50 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Process:</h4>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $req->process_steps }}</p>
                    </div>
                    @endif

                    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-gray-500">
                        @if($req->office_responsible)
                        <span><i class="fas fa-building mr-1"></i>{{ $req->office_responsible }}</span>
                        @endif
                        @if($req->deadline)
                        <span class="text-red-600 font-medium"><i class="fas fa-calendar-alt mr-1"></i>Due: {{ $req->deadline->format('d M Y') }}</span>
                        @endif
                        @if($req->link_to_form)
                        <a href="{{ $req->link_to_form }}" class="text-red-800 hover:underline font-medium"><i class="fas fa-download mr-1"></i>Download Form</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── Section 3: Downloadable Forms ───────────────────────────────────── --}}
    <section id="forms">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-file-download text-red-800"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Downloadable Forms</h2>
        </div>

        @if($immigrationForms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($immigrationForms as $form)
            @php $formUrl = route('information.resources.download', $form); @endphp
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6 flex flex-col">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                        <i class="fas fa-file-pdf text-red-800"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $form->title }}</h3>
                        @if($form->description)
                        <p class="text-sm text-gray-500 mt-1">{{ $form->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-auto pt-4 flex gap-3">
                    <a href="{{ $formUrl }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-red-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        <i class="fas fa-download"></i> Download
                    </a>
                    <a href="{{ $formUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl shadow border border-dashed border-gray-300 p-10 text-center">
            <i class="fas fa-file-upload text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">Forms will appear here once uploaded by the administrator.</p>
            <p class="text-sm text-gray-400 mt-1">Contact the International Students Office for physical copies in the meantime.</p>
        </div>
        @endif
    </section>

    {{-- ── Section 4: Application Instructions ────────────────────────────── --}}
    <section id="instructions">
        <div class="bg-white rounded-2xl shadow p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-list-ol text-red-800"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Application Instructions</h2>
            </div>
            <div class="space-y-4">
                @php
                $steps = [
                    ['title' => 'Download and complete the application form', 'desc' => 'Use the official study permit application form from the Department of Immigration. Fill in all sections clearly in block letters.'],
                    ['title' => 'Gather all required documents', 'desc' => 'Collect all documents listed in the requirements section above. Ensure certified copies where required and that your passport has sufficient validity.'],
                    ['title' => 'Submit at the immigration office', 'desc' => 'Bring your completed application and all supporting documents to the nearest Department of Immigration and Citizenship Services office.'],
                    ['title' => 'Pay the required application fees', 'desc' => 'Pay the applicable permit fees at the cashier. Keep your payment receipt — it may be needed to collect your permit.'],
                    ['title' => 'Wait for processing', 'desc' => 'Processing typically takes 4–8 weeks. You may be contacted for an interview or for additional documents. Check the status at the office if you have not received a response after 8 weeks.'],
                    ['title' => 'Collect your study permit', 'desc' => 'Once approved, collect your permit in person. Ensure you register with UB\'s International Students Office and keep your permit valid throughout your studies.'],
                ];
                @endphp
                @foreach($steps as $i => $step)
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-red-800 text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">{{ $i + 1 }}</div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Section 5: Useful Links ──────────────────────────────────────────── --}}
    <section id="links">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-link text-red-800"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Useful Links</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            {{-- Static core links --}}
            @php
            $staticLinks = [
                ['title' => 'Botswana Department of Immigration', 'desc' => 'Official government immigration portal for Botswana.', 'url' => 'https://www.gov.bw/immigration', 'icon' => 'fa-globe'],
                ['title' => 'University of Botswana — International Students', 'desc' => 'UB official page for international student support and services.', 'url' => 'https://www.ub.bw', 'icon' => 'fa-university'],
                ['title' => 'Botswana eVisa Portal', 'desc' => 'Apply for visas and check visa requirements online.', 'url' => 'https://evisa.gov.bw', 'icon' => 'fa-passport'],
            ];
            @endphp
            @foreach($staticLinks as $link)
            <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="bg-white rounded-2xl shadow border border-gray-100 p-5 flex items-start gap-4 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                    <i class="fas {{ $link['icon'] }} text-red-800"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-red-800 transition">{{ $link['title'] }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $link['desc'] }}</p>
                </div>
                <i class="fas fa-external-link-alt text-gray-400 text-xs ml-auto mt-1 shrink-0"></i>
            </a>
            @endforeach
            {{-- Dynamic links from DB --}}
            @foreach($immigrationLinks as $link)
            @php $linkUrl = $link->external_link ?: ($link->file_path ? route('information.resources.download', $link) : '#'); @endphp
            <a href="{{ $linkUrl }}" target="_blank" rel="noopener" class="bg-white rounded-2xl shadow border border-gray-100 p-5 flex items-start gap-4 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-link text-red-800"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-red-800 transition">{{ $link->title }}</h3>
                    @if($link->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $link->description }}</p>
                    @endif
                </div>
                <i class="fas fa-external-link-alt text-gray-400 text-xs ml-auto mt-1 shrink-0"></i>
            </a>
            @endforeach
        </div>
    </section>

    {{-- ── Section 6: Immigration Office Locations ─────────────────────────── --}}
    <section id="offices">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-map-marker-alt text-red-800"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Immigration Office Locations</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Static: main immigration office --}}
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 text-lg">Department of Immigration & Citizenship Services</h3>
                <p class="text-sm text-gray-500 mt-1">Main Office — Gaborone</p>
                <div class="mt-4 space-y-2 text-sm text-gray-700">
                    <p><i class="fas fa-map-marker-alt text-red-800 mr-2 w-4"></i>Private Bag 00154, Gaborone, Botswana</p>
                    <p><i class="fas fa-phone text-red-800 mr-2 w-4"></i>+267 364 1400</p>
                    <p><i class="fas fa-clock text-red-800 mr-2 w-4"></i>Monday – Friday: 07:30 – 16:30</p>
                    <p class="text-gray-500 text-xs mt-2">Closed on public holidays.</p>
                </div>
                <a href="https://maps.google.com/?q=Department+of+Immigration+Gaborone+Botswana" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 text-sm text-red-800 hover:underline font-medium">
                    <i class="fas fa-map"></i> Open in Google Maps
                </a>
            </div>
            {{-- Dynamic: offices from DB --}}
            @foreach($immigrationOffices as $office)
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 text-lg">{{ $office->office_name }}</h3>
                @if($office->description)
                <p class="text-sm text-gray-500 mt-1">{{ $office->description }}</p>
                @endif
                <div class="mt-4 space-y-2 text-sm text-gray-700">
                    @if($office->building)
                    <p><i class="fas fa-map-marker-alt text-red-800 mr-2 w-4"></i>{{ $office->building }}</p>
                    @endif
                    @if($office->phone)
                    <p><i class="fas fa-phone text-red-800 mr-2 w-4"></i>{{ $office->phone }}</p>
                    @endif
                    @if($office->hours)
                    <p><i class="fas fa-clock text-red-800 mr-2 w-4"></i>{{ $office->hours }}</p>
                    @endif
                    @if($office->email)
                    <p><i class="fas fa-envelope text-red-800 mr-2 w-4"></i>{{ $office->email }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── Section 7: Important Notes ──────────────────────────────────────── --}}
    <section id="notes">
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle text-amber-700"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Important Notes</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-gray-700">
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <h3 class="font-semibold text-gray-900 mb-2"><i class="fas fa-hourglass-half text-red-800 mr-2"></i>Processing Times</h3>
                    <p>Standard processing takes <strong>4–8 weeks</strong>. Apply well in advance — delays are common during peak intake periods (January and August).</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <h3 class="font-semibold text-gray-900 mb-2"><i class="fas fa-calendar-check text-red-800 mr-2"></i>Arrival Deadlines</h3>
                    <p>You must begin the permit application process <strong>within 30 days of arrival</strong> in Botswana if you entered on a visitor's permit or visa-free entry.</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <h3 class="font-semibold text-gray-900 mb-2"><i class="fas fa-sync-alt text-red-800 mr-2"></i>Renewals</h3>
                    <p>Study permits must be renewed annually. Begin the renewal process at least <strong>2 months before expiry</strong> to avoid gaps in legal status.</p>
                </div>
            </div>
        </div>
    </section>

</div>

{{-- ── CTA ────────────────────────────────────────────────────────────────── --}}
<div class="bg-gradient-to-r from-red-900 to-red-800 text-white py-12 mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold mb-2">Need accommodation proof for your permit?</h2>
        <p class="opacity-90 mb-6">UB-UniStay can help you secure verified off-campus housing and generate accommodation confirmation documents.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ url('/properties') }}" class="bg-white text-red-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">Browse Properties</a>
            @guest
            <a href="{{ route('register') }}" class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">Create Account</a>
            @endguest
        </div>
    </div>
</div>

@endsection
