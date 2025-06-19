<div>
    <div class="container mx-auto p-4 sm:p-6 lg:p-8 max-w-6xl">
        @if (session('success'))
            <div id="successAlert"
                class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300 transition-opacity duration-500">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div id="errorAlert"
                class="mb-4 p-3 rounded bg-red-100 text-grey-800 border border-red-300 transition-opacity duration-500">
                {{ session('error') }}
            </div>
        @endif


        {{-- Header Section --}}
        <header class="flex flex-col items-center justify-center mb-12 mt-8 text-center"> {{-- Added flex-col and text-center for better mobile alignment --}}
            <i class="fas fa-gamepad text-5xl text-blue-600 mb-4"></i> {{-- Moved icon above title for vertical stacking on small screens --}}
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 drop-shadow-sm">🎮 Game Tester</h1>
            {{-- Adjusted text size for smaller screens --}}
        </header>

        {{-- How It Works / Steps Section --}}
        <section class="mb-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6"> {{-- Stays as 1 column on mobile, 3 on medium screens and up --}}
                {{-- Step 1: Enter Your PayPal --}}
                <div
                    class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center text-center transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="bg-blue-100 text-blue-600 p-4 rounded-full mb-4 text-3xl">
                        <i class="fa-brands fa-paypal"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Step 1: Enter Your PayPal</h3>
                    <p class="text-gray-600 text-sm">Securely link your PayPal for payouts.</p>
                </div>
                {{-- Step 2: Play Games --}}
                <div
                    class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center text-center transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="bg-green-100 text-green-600 p-4 rounded-full mb-4 text-3xl">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Step 2: Play Games</h3>
                    <p class="text-gray-600 text-sm">Enjoy testing games and earning rewards.</p>
                </div>
                {{-- Step 3: Click Withdraw! --}}
                <div
                    class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center text-center transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="bg-purple-100 text-purple-600 p-4 rounded-full mb-4 text-3xl">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Step 3: Click Withdraw!</h3>
                    <p class="text-gray-600 text-sm">Cash out your earnings directly to PayPal.</p>
                </div>
            </div>
        </section>

        {{-- Wallet and PayPal Update Section --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12"> {{-- Stacks on mobile, two columns on large screens --}}
            {{-- Current Wallet Card --}}
            <div x-data="{ activeTab: 'inProgress' }"
                class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-8 rounded-2xl shadow-2xl flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-semibold mb-2 opacity-90">Your Current Wallet</h2>
                    <p class="text-5xl sm:text-6xl font-extrabold mb-6 drop-shadow-lg">$0.00</p> {{-- Adjusted text size for smaller screens --}}
                </div>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3"> {{-- Buttons stack on mobile, row on sm screens and up --}}
                    <button @click="activeTab = 'history'"
                        class="flex-1 py-3 px-6 rounded-full bg-yellow-400 text-yellow-900 font-bold hover:bg-yellow-300 transition-colors duration-200 shadow-md">
                        History
                    </button>
                    <button
                        class="flex-1 py-3 px-6 rounded-full bg-yellow-400 text-yellow-900 font-bold hover:bg-yellow-300 transition-colors duration-200 shadow-md">
                        In Progress
                    </button>
                    <button @click="alert('Withdrawal initiated! (Simulation)')"
                        class="flex-1 py-3 px-6 rounded-full bg-yellow-400 text-yellow-900 font-bold hover:bg-yellow-300 transition-colors duration-200 shadow-md">
                        Withdraw
                    </button>
                </div>
            </div>

            {{-- Update PayPal Email Card --}}
            <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col justify-between">
                <div>
                    @if (isset($result))
                        <p>wade goda</p>
                    @endif

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Update PayPal Email</h2>
                    <p class="text-gray-600 mb-4">Ensure your PayPal email is correct for smooth payouts.</p>
                    <form action="" id="paypalForm">

                        <div class="relative mb-4">
                            <input wire:model="email" type="email" placeholder="you@example.com" id="paypalEmail"
                                name="email" value="{{ Session::get('email') }}  "
                                class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 text-gray-700 text-lg"
                                aria-label="Enter your PayPal Email">
                        </div>

                        <button id="btn" wire:click.prevent ="SaveTodb"
                            class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-bold text-lg hover:bg-green-700 transition-colors duration-300 shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                            <span>Save Email</span>
                        </button>
                    </form>
                    {{-- <p id="para" class="text-green-600 text-sm mt-3 text-center hidden">Email saved successfully!</p> --}}
                </div>
            </div>
        </section>

        {{-- Available Games Section --}}
        <section>
            <h2 class="text-3xl flex items-center justify-center font-bold text-gray-900 mb-8 text-center">Available
                Games
                to Test</h2>
            <div style="display: {{ $is_turnstile }};">
                <input type="hidden" id="cf-turnstile-response" wire:model.defer="turnstileToken">
                <div class="text-gray-500 text-center rounded-xl p-5 " id="capture">
                    <div wire:ignore x-data x-init="window.onTurnstileSuccess = (token) => $wire.set('turnstileToken', token)">
                        <div class=" text-gray-500 text-center  rounded-xl p-5 cf-turnstile flex items-center justify-center"
                            data-sitekey="{{ config('services.turnstile.key') }}" data-theme="{{ $theme ?? 'light' }}"
                            data-callback="onTurnstileSuccess" data-size="normal">
                            {{-- <p class="text-sm">Please complete the captcha</p> --}}
                        </div>
                        {{-- <button
                            class="bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors duration-300 shadow-md"
                            wire:click="capture">Get games</button> --}}
                    </div>
                </div>
            </div>

            {{-- class="text-gray-500 text-center rounded-xl p-5 cf-turnstile flex items-center justify-center" --}}


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 " id="GameList">
                {{-- Stays as 1 column on mobile, adapts to 2 or 3 --}}
                @foreach ($games as $index => $game)
                    <div
                        class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center text-center
                        transition duration-300 hover:scale-[1.03] hover:shadow-2xl">
                        {{-- thumbnail --}}
                        <img src="{{ $game['thumbnail'] }}" alt="{{ $game['title'] }}"
                            class="w-full max-w-xs h-auto object-cover mb-4 border-4 border-red-500 shadow-lg rounded-lg">
                        {{-- Made image fully responsive and added rounded-lg --}}

                        {{-- title --}}
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2"> {{-- Adjusted text size for smaller screens --}}
                            {{ $game['title'] }}
                        </h3>

                        {{-- price --}}
                        <p class="text-lg text-gray-600 mb-4">
                            Earn:
                            <span class="text-green-600 font-extrabold text-xl sm:text-2xl"> {{-- Adjusted text size for smaller screens --}}
                                {{ $game['price'] }}
                            </span>
                        </p>

                        {{-- play button with unique ID --}}
                        <button id="openModalBtn-{{ $index }}" data-index="{{ $index }}" type="button"
                            class="openModalBtn w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg
                            hover:bg-blue-700 transition-colors duration-300 shadow-md">
                            Play Now
                        </button>
                    </div>
                    {{-- wire:click.prevent=openModel({{$index}}) --}}
                    {{-- Modal Overlay (unique per item) --}}
                    <div id="jackpotModal-{{ $index }}"
                        class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 hidden z-50">
                        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto overflow-hidden transform transition-all duration-300 scale-95 opacity-0"
                            id="modalContent-{{ $index }}">
                            {{-- Modal Header --}}
                            <div
                                class="flex justify-between items-center bg-gradient-to-r from-green-700 to-green-600 text-white px-6 py-4 rounded-t-xl shadow-md">
                                <h2 class="text-xl-center sm:text-2xl  font-bold">{{ $game['title'] }}</h2>
                                {{-- Adjusted text size for smaller screens --}}
                                <button
                                    class="closeModalBtn text-white hover:text-gray-200 focus:outline-none transition-transform duration-200 transform hover:scale-110"
                                    data-index="{{ $index }}">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- Modal Body --}}
                            <div class="modal-body-scrollable  p-4 max-h-[80vh] overflow-y-auto"> {{-- Added max-h for scroll on smaller modals, overflow-y-auto --}}
                                <div class="mb-4 rounded-lg flex justify-center overflow-hidden shadow-lg">
                                    {{-- Centered image --}}
                                    <img src="{{ $game['thumbnail'] }}" alt="Game Preview"
                                        class="w-full h-auto object-cover rounded-lg"> {{-- Made image fully responsive within its container --}}
                                </div>

                                <p class="text-gray-700 text-base leading-relaxed mb-4"> {{-- Changed text-md-start to text-base and increased mb --}}
                                    {{ $game['description'] }}
                                </p>

                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Tasks
                                </h3>

                                {{-- example tasks --}}
                                @foreach ($game['events'] as $task)
                                    <div class="space-y-[4px] mb-2"> {{-- Reduced mb for tighter spacing --}}
                                        <div
                                            class="flex items-center justify-between bg-gray-50 border p-3 rounded-lg shadow-sm">
                                            <span>{{ $task['name'] }}</span>
                                            <span class="text-green-600 font-semibold">{{ $task['points'] }}</span>
                                        </div>
                                    </div>
                                @endforeach

                                <p class="text-red-700 text-sm text-base leading-relaxed mb-4"> {{-- Changed text-md-start to text-base and increased mb --}}
                                    Disclaimer :
                                </p>
                                <p class="text-gray-700 text-sm text-base leading-relaxed mb-4"> {{-- Changed text-md-start to text-base and increased mb --}}
                                    {{ $game['disclaimer'] }}
                                </p>
                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-transparent  shadow-lg">
                                    {{-- This is the key change --}}

                                    <button
                                        class="btn-gradient w-full py-4 text-white font-bold text-lg rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                                        <a href="{{ $game['play_url'] }}" target="_blank">PLAY AND EARN
                                            {{ $game['price'] }}</a>
                                    </button>
                                </div>
                                {{-- @php
                                    dd(isset($result));
                                @endphp --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>


<script>
    window.addEventListener('model', () => {

        const openButtons = document.querySelectorAll('.openModalBtn');
        const closeButtons = document.querySelectorAll('.closeModalBtn');

        /* ------- OPEN ------- */
        openButtons.forEach(button => {
            button.addEventListener('click', () => {
                alert('clicked');

                const index = button.dataset.index;
                const modal = document.getElementById(`jackpotModal-${index}`);
                const modalContent = document.getElementById(`modalContent-${index}`);
                var open = document.getElementById('successAlert');

                if (!email) {
                    alert('Please enter your PayPal email first ❗');
                    return;
                }


                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                    document.body.classList.add('overflow-hidden');
                }, 50);

            });
        });

        /* ------- CLOSE (same as before) ------- */
        closeButtons.forEach(button => {}

        )


        document.addEventListener('DOMContentLoaded', () => {
            console.log('hare');
            var email = document.getElementById('paypalEmail').value.trim();
            const openButtons = document.querySelectorAll('.openModalBtn');
            const closeButtons = document.querySelectorAll('.closeModalBtn');
            var open = document.getElementById('successAlert');



        });


        button.addEventListener('click', () => {
            const index = button.dataset.index;
            const modal = document.getElementById(`jackpotModal-${index}`);
            const modalContent = document.getElementById(`modalContent-${index}`);
            document.body.classList.remove('overflow-hidden');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        });
    });

    // Close modal when clicking outside
    document.querySelectorAll('[id^="jackpotModal-"]').forEach(modal => {
        modal.addEventListener('click', event => {
            const content = modal.querySelector('[id^="modalContent-"]');
            if (event.target === modal) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');

                }, 300);
            }
        });
    });
</script>

<script>
    document.getElementById('paypalForm').addEventListener('submit', function(event) {
        var email = document.getElementById('paypalEmail').value.trim();
        //   const modal = document.getElementById(`jackpotModal-${index}`);
        var para = document.getElementById('para');

        if (!email) {
            alert('Please enter your PayPal email.');
            event.preventDefault();
        } else {
            // event.preventDefault();
            para.classList.remove('hidden');
        }
    });
</script>
<script>
    // Wait 8 seconds, then fade out the alert
    setTimeout(() => {
        var alert = document.getElementById('successAlert');
        if (alert) {
            alert.style.opacity = '0';
            // wait for fade-out transition, then remove
        }
    }, 8000); // 8000ms = 8s
</script>
