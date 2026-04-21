@extends('layouts.public')

@section('title', 'Student Resources & Living Guide')

@section('content')

{{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
<div class="bg-gradient-to-r from-red-900 to-red-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-widest text-red-300 font-semibold mb-2">Information Hub</p>
                <h1 class="text-4xl md:text-5xl font-bold">Student Resources & Living Guide</h1>
                <p class="mt-3 text-lg opacity-90 max-w-2xl">Transport, shopping, health services, and everything you need to settle into life around the University of Botswana.</p>
                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="#transport" class="bg-white text-red-800 px-5 py-2.5 rounded-lg font-semibold hover:bg-gray-100 transition text-sm">Transport</a>
                    <a href="#malls" class="border-2 border-white text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition text-sm">Shopping Malls</a>
                    <a href="#library" class="border-2 border-white text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition text-sm">Resource Library</a>
                </div>
            </div>
            <a href="{{ url('/information-hub') }}" class="text-white hover:underline text-sm flex items-center gap-2 shrink-0">
                <i class="fas fa-arrow-left"></i> Back to Information Hub
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">

    {{-- ── Section 1: Transport Services ──────────────────────────────────── --}}
    <section id="transport">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-car text-red-800"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Transport Services</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- inDrive --}}
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6 flex flex-col">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-gray-900 flex items-center justify-center shrink-0">
                        <i class="fas fa-car-side text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">inDrive</h3>
                        <p class="text-sm text-gray-500">Ride-hailing app</p>
                    </div>
                </div>
                <p class="text-gray-700 text-sm leading-relaxed flex-1">inDrive lets you negotiate your own fare directly with the driver, making it one of the more affordable options in Gaborone. Widely used by students for trips around the city and to campus. Available on Android and iOS.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="https://indrive.com" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                        <i class="fas fa-external-link-alt"></i> Visit Website
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=sinet.startup.inDriver" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                        <i class="fab fa-google-play"></i> Google Play
                    </a>
                </div>
            </div>

            {{-- Yango --}}
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6 flex flex-col">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-red-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-taxi text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Yango</h3>
                        <p class="text-sm text-gray-500">Ride-hailing app</p>
                    </div>
                </div>
                <p class="text-gray-700 text-sm leading-relaxed flex-1">Yango is another popular ride-hailing service operating in Gaborone. It offers fixed upfront pricing, quick pickups, and in-app payment options. Recommended for reliable trips across the city, including to and from the airport.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="https://yango.com" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        <i class="fas fa-external-link-alt"></i> Visit Website
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=ru.yandex.taxi" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                        <i class="fab fa-google-play"></i> Google Play
                    </a>
                </div>
            </div>
        </div>

        {{-- Tip --}}
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3 text-sm text-blue-800">
            <i class="fas fa-lightbulb text-blue-500 mt-0.5"></i>
            <p><strong>Tip:</strong> Gaborone also has public minibus taxis (combis) which are the cheapest option. Ask locals or fellow students about routes. Keep small change handy as they rarely give change for large notes.</p>
        </div>
    </section>

    {{-- ── Section 2: Nearby Shopping Malls ───────────────────────────────── --}}
    <section id="malls">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-shopping-bag text-red-800"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Nearby Shopping Malls</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $malls = [
                [
                    'name'     => 'Main Mall',
                    'area'     => 'City Centre, Gaborone',
                    'distance' => '≈ 3 km from UB',
                    'hours'    => 'Mon–Sat: 08:00–18:00  |  Sun: 09:00–14:00',
                    'note'     => 'Gaborone\'s original central shopping strip. Banks, pharmacies, clothing and food are all available here. Easy to reach by combi or taxi.',
                    'map'      => 'https://maps.google.com/?q=Main+Mall+Gaborone',
                    'icon'     => 'fa-store',
                    'color'    => 'bg-blue-600',
                ],
                [
                    'name'     => 'Riverwalk Mall',
                    'area'     => 'Riverwalk, Gaborone',
                    'distance' => '≈ 4 km from UB',
                    'hours'    => 'Mon–Sat: 09:00–20:00  |  Sun: 10:00–18:00',
                    'note'     => 'Mid-sized mall with a supermarket, pharmacy, restaurants, and a cinema. Popular with students for affordable food options and a relaxed atmosphere.',
                    'map'      => 'https://maps.google.com/?q=Riverwalk+Mall+Gaborone',
                    'icon'     => 'fa-shopping-cart',
                    'color'    => 'bg-green-700',
                ],
                [
                    'name'     => 'Game City Mall',
                    'area'     => 'Gaborone West',
                    'distance' => '≈ 6 km from UB',
                    'hours'    => 'Mon–Sat: 09:00–20:00  |  Sun: 10:00–18:00',
                    'note'     => 'One of the largest malls in Botswana. Anchored by a large Pick n Pay and Game store. Great for electronics, furniture, and bulk grocery shopping.',
                    'map'      => 'https://maps.google.com/?q=Game+City+Mall+Gaborone',
                    'icon'     => 'fa-building',
                    'color'    => 'bg-purple-700',
                ],
            ];
            @endphp
            @foreach($malls as $mall)
            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden flex flex-col">
                <div class="h-2 {{ $mall['color'] }}"></div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl {{ $mall['color'] }} flex items-center justify-center shrink-0">
                            <i class="fas {{ $mall['icon'] }} text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $mall['name'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $mall['area'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed flex-1">{{ $mall['note'] }}</p>
                    <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                        <p><i class="fas fa-route text-red-800 mr-2 w-4"></i>{{ $mall['distance'] }}</p>
                        <p><i class="fas fa-clock text-red-800 mr-2 w-4"></i>{{ $mall['hours'] }}</p>
                    </div>
                    <a href="{{ $mall['map'] }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 text-sm text-red-800 hover:underline font-medium">
                        <i class="fas fa-map-marker-alt"></i> Open in Maps
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── Section 3: Health Services ───────────────────────────────────────── --}}
    <section id="health">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-heartbeat text-red-800"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Health Services</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-1"><i class="fas fa-hospital text-red-800 mr-2"></i>UB Health Centre</h3>
                <p class="text-sm text-gray-600 mb-3">On-campus clinic for registered UB students. Provides general consultations, basic medications, and referrals.</p>
                <p class="text-sm text-gray-700"><i class="fas fa-map-marker-alt text-red-800 mr-2"></i>University of Botswana Campus</p>
                <p class="text-sm text-gray-700 mt-1"><i class="fas fa-clock text-red-800 mr-2"></i>Mon–Fri: 07:30–16:30</p>
            </div>
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-1"><i class="fas fa-clinic-medical text-red-800 mr-2"></i>Princess Marina Hospital</h3>
                <p class="text-sm text-gray-600 mb-3">The main government referral hospital in Gaborone. Available for emergencies and specialist consultations.</p>
                <p class="text-sm text-gray-700"><i class="fas fa-map-marker-alt text-red-800 mr-2"></i>Notwane Rd, Gaborone</p>
                <p class="text-sm text-gray-700 mt-1"><i class="fas fa-phone text-red-800 mr-2"></i>+267 395 3221</p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                <h3 class="font-bold text-red-900 mb-3"><i class="fas fa-phone-alt mr-2"></i>Emergency Numbers</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span class="text-gray-700">Police</span><span class="font-bold text-gray-900">999</span></li>
                    <li class="flex justify-between"><span class="text-gray-700">Ambulance / Medical</span><span class="font-bold text-gray-900">997</span></li>
                    <li class="flex justify-between"><span class="text-gray-700">Fire Brigade</span><span class="font-bold text-gray-900">998</span></li>
                    <li class="flex justify-between"><span class="text-gray-700">Combined Emergency</span><span class="font-bold text-gray-900">112</span></li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ── Section 4: Currency & Payments ──────────────────────────────────── --}}
    <section id="currency">
        <div class="bg-white rounded-2xl shadow p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-money-bill-wave text-red-800"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Currency & Payments</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Local Currency</h3>
                    <p class="text-gray-700">Botswana's currency is the <strong>Pula (BWP)</strong>. 1 Pula = 100 Thebe. Notes come in P10, P20, P50, P100, and P200. Coins are available in 5t, 10t, 25t, 50t, and P1, P2, P5.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">ATMs & Banks</h3>
                    <p class="text-gray-700">ATMs are widely available at malls, petrol stations, and on campus. Major banks include <strong>Stanbic, FNB, Barclays (Absa), and Standard Chartered</strong>. Most accept international Visa/Mastercard.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Mobile Money</h3>
                    <p class="text-gray-700"><strong>Orange Money</strong> and <strong>Smega (Mascom)</strong> are widely used for transfers, bill payments, and even paying rent. Ask your landlord if they accept mobile money.</p>
                </div>
            </div>
            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800 flex items-start gap-3">
                <i class="fas fa-lightbulb text-amber-500 mt-0.5"></i>
                <p>Open a local bank account as soon as possible — it makes paying rent, utilities, and fees much easier. Bring your passport, student ID, and proof of residence to the bank.</p>
            </div>
        </div>
    </section>

    {{-- ── Section 5: Quick Access Links ───────────────────────────────────── --}}
    <section id="quick-links">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-bolt text-red-800"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Quick Access Links</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            @php
            $studentPortalUrl = auth()->check() && auth()->user()->isStudent()
                ? route('student.dashboard')
                : route('login');
            $quickLinks = [
                ['title' => 'My Dashboard', 'desc' => 'Access your bookings, applications, and account.', 'url' => $studentPortalUrl, 'icon' => 'fa-graduation-cap', 'color' => 'bg-blue-600', 'external' => false],
                ['title' => 'UB-UniStay — Find Housing', 'desc' => 'Browse verified off-campus properties near UB.', 'url' => url('/properties'), 'icon' => 'fa-home', 'color' => 'bg-red-800', 'external' => false],
                ['title' => 'Immigration Guide', 'desc' => 'Study permit requirements and downloadable forms.', 'url' => route('information.immigration'), 'icon' => 'fa-passport', 'color' => 'bg-green-700', 'external' => false],
                ['title' => 'UB Official Website', 'desc' => 'News, academic calendar, and university contacts.', 'url' => 'https://www.ub.bw', 'icon' => 'fa-university', 'color' => 'bg-purple-700', 'external' => true],
            ];
            @endphp
            @foreach($quickLinks as $link)
            <a href="{{ $link['url'] }}" target="{{ $link['external'] ? '_blank' : '_self' }}" rel="noopener" class="bg-white rounded-2xl shadow border border-gray-100 p-5 hover:shadow-md transition flex items-start gap-4 group">
                <div class="w-10 h-10 rounded-xl {{ $link['color'] }} flex items-center justify-center shrink-0">
                    <i class="fas {{ $link['icon'] }} text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-red-800 transition text-sm">{{ $link['title'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $link['desc'] }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- ── Section 6: Resource Library (dynamic) ───────────────────────────── --}}
    <section id="library">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-book-open text-red-800"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Resource Library</h2>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <form method="GET" action="{{ route('information.resources') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Search titles or descriptions…">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="">All types</option>
                        @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex flex-wrap gap-3">
                    <button type="submit" class="bg-red-800 text-white px-6 py-2.5 rounded-lg font-semibold text-sm hover:bg-red-900 transition">Filter</button>
                    <a href="{{ route('information.resources') }}#library" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">Reset</a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($resources as $resource)
            @php
                $resourceUrl = ($resource->file_path || $resource->external_link)
                    ? route('information.resources.download', $resource)
                    : null;
                $isLink = in_array($resource->type, ['link', 'video']);
                $actionLabel = $isLink ? 'Open Resource' : 'Download';
                $iconClass = match($resource->type) {
                    'document' => 'fa-file-pdf',
                    'video'    => 'fa-video',
                    'link'     => 'fa-link',
                    default    => 'fa-file',
                };
            @endphp
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6 flex flex-col">
                <div class="flex items-start justify-between gap-4 mb-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                            <i class="fas {{ $iconClass }} text-red-800"></i>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-red-700 font-semibold">{{ ucfirst(str_replace('_', ' ', $resource->type)) }}</p>
                            <h3 class="text-lg font-bold text-gray-900 mt-0.5">{{ $resource->title }}</h3>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs shrink-0">{{ ucfirst(str_replace('_', ' ', $resource->category)) }}</span>
                </div>

                <p class="text-gray-600 text-sm flex-1">{{ $resource->description ?: 'No description available.' }}</p>

                @if(is_array($resource->tags) && count($resource->tags) > 0)
                <div class="flex flex-wrap gap-1.5 mt-3">
                    @foreach($resource->tags as $tag)
                    <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif

                <div class="mt-5 flex items-center justify-between gap-4">
                    <p class="text-xs text-gray-400">{{ number_format($resource->download_count) }} {{ $isLink ? 'visits' : 'downloads' }}</p>
                    @if($resourceUrl)
                    <a href="{{ $resourceUrl }}" class="inline-flex items-center gap-2 bg-red-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        <i class="fas {{ $isLink ? 'fa-external-link-alt' : 'fa-download' }}"></i>{{ $actionLabel }}
                    </a>
                    @else
                    <span class="text-xs text-gray-400">Not available yet</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl shadow p-12 text-center">
                <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No resources matched your filters.</p>
                <a href="{{ route('information.resources') }}#library" class="mt-3 inline-block text-sm text-red-800 hover:underline">Clear filters</a>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $resources->links() }}
        </div>
    </section>

</div>

{{-- ── CTA ────────────────────────────────────────────────────────────────── --}}
<div class="bg-gradient-to-r from-red-900 to-red-800 text-white py-12 mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold mb-2">Looking for off-campus accommodation?</h2>
        <p class="opacity-90 mb-6">UB-UniStay connects students with verified landlords and properties near the University of Botswana.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ url('/properties') }}" class="bg-white text-red-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">Browse Properties</a>
            @guest
            <a href="{{ route('register') }}" class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">Create Account</a>
            @endguest
        </div>
    </div>
</div>

@endsection
