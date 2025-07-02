<div>
    <div class="container mx-auto p-2 sm:p-4 lg:p-2 max-w-6xl ">
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
        <header class="flex flex-col items-center justify-content-start mb-12 text-center"> {{-- Added flex-col and text-center for better mobile alignment --}}
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
                        <i class="fa-brands fa-paypal "></i>
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
                    <p class="text-5xl sm:text-6xl font-extrabold mb-6 drop-shadow-lg">$ {{ $UserBalance }} </p>
                    {{-- Adjusted text size for smaller screens --}}
                </div>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3"> {{-- Buttons stack on mobile, row on sm screens and up --}}
                    <button wire:click.prevent = "History" type="button"
                        onclick="document.body.classList.add('overflow-hidden');
"
                        class="flex-1 py-3 px-6 rounded-full bg-yellow-400 text-yellow-900 font-bold hover:bg-yellow-300 transition-colors duration-200 shadow-md">
                        History
                    </button>
                    <button wire:click.prevent = "Inprogress" onclick="document.body.classList.add('overflow-hidden');"
                        class="flex-1 py-3 px-6 rounded-full bg-yellow-400 text-yellow-900 font-bold hover:bg-yellow-300 transition-colors duration-200 shadow-md">
                        In Progress
                    </button>
                    <button wire:click.prevent = "withdraw" type="button" {{ $withdrawShow }}
                        class="flex-1 py-3 px-6 rounded-full bg-yellow-400 text-yellow-900 font-bold hover:bg-yellow-300 transition-colors duration-200 shadow-md">
                        Withdraw
                    </button>
                </div>
                {{-- <button wire:click.prevent = "addWallet" type="button" {{ $addShow }}
                    class="mt-3  rounded-full bg-yellow-400 text-yellow-900 font-bold hover:bg-yellow-300 transition-colors duration-200 shadow-md">
                    Add Wallet 'for tesing'
                </button> --}}

            </div>

            {{-- Enter PayPal Email Card --}}
            <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col justify-between"
                style="display: {{ $paypalnewCard }};">

                <h2 class="text-2xl font-bold text-gray-800 mb-4 ">Log with PayPal Email</h2>
                <p class="text-gray-600 mb-4">Ensure your PayPal email is correct for smooth payouts.</p>
                <form action="" id="paypalForm">

                    <div class="relative mb-4" id="mail" style="display: {{ $mailLock }};">
                        <input wire:model="email" type="email" placeholder="you@example.com" id="paypalEmail"
                            {{ $updatedInputF }} name="email" value="  "
                            class="w-full px-5 py-3 border disabled border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 text-gray-700 text-lg"
                            aria-label="Enter your PayPal Email">
                        @error('email')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <button {{ $saveButtonDisabled }} id="btn" wire:click.prevent ="SaveTodb" {{ $updatedBtn }}
                        class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-bold text-lg hover:bg-green-700 transition-colors duration-300 shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                        <span>Save Email</span>
                    </button>
                </form>

            </div>

            {{-- update PayPal Email Card --}}
            <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col justify-between"
                style="display: {{ $paypalUpdateCard }} ;">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Update your PayPal Email</h2>

                    <p id="quote1" class="text-gray-600 mb-4">Ensure your PayPal email is
                        correct for smooth payouts.</p>
                    <p id="quote2" class="text-red-600 mb-4 hidden ">You can only update your Paypal
                        Email only after 30 days again </p>
                    <form action="" id="paypalForm">
                        <div class="relative mb-4 " id="mail">
                            <input type="email" placeholder="you@example.com" {{ $updatedInputF }} id="paypalEmail1"
                                name="Uemail"wire:model="Uemail"
                                class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 text-gray-700 text-lg"
                                aria-label="Enter your PayPal Email">
                            @error('Uemail')
                                <span class="text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <p id="openModel" style="display: {{ $show }};" class="text-gray-600 mb-4 hidden"> </p>
                        <button id="btn" onclick="confirmation()" type="button"
                            class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-bold text-lg hover:bg-green-700 transition-colors duration-300 shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                            {{ $updatedBtn }}>
                            <span>Update Email</span>
                        </button>
                        <button id="confirm_btn" wire:click.prevent ="UpEmail" class="hidden" type="button"></button>

                    </form>

                </div>
            </div>
        </section>
    </div>




    {{-- Available Games Section --}}
    <section>
        <h2 class="text-3xl flex items-center justify-center font-bold text-gray-900 mb-8 text-center">Available
            Games
            to Test</h2>
        <div style="display: {{ $isTurnstile }};">
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
                    <img id="openModalBtn-{{ $index }}" {{-- UNIQUE id --}}
                        data-index="{{ $index }}" {{-- tells JS which modal --}} src="{{ $game['thumbnail'] }}"
                        class="openModalBtn cursor-pointer w-full max-w-xs h-[330px] object-cover mb-4 border-2 border-white shadow-lg rounded-lg"
                        alt="Open jackpot {{ $index }}" />

                    <div class="h-16">

                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2"> {{-- Adjusted text size for smaller screens --}}
                            {{ $game['title'] }}
                        </h3>
                    </div>

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
                            hover:bg-blue-700 transition-colors duration-300 shadow-md cursor-pointer">
                        Play Now
                    </button>
                </div>
                {{-- wire:click.prevent=openModel({{$index}}) --}}
                {{-- Modal Overlay (unique per item) --}}
                <div id="jackpotModal-{{ $index }}"
                    class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 hidden z-100">
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
                        <div class="modal-body-scrollable  p-4 max-h-[85vh] overflow-y-auto">
                            {{-- Added max-h for scroll on smaller modals, overflow-y-auto --}}
                            <div class="mb-4 rounded-lg flex justify-center overflow-hidden shadow-lg">
                                {{-- Centered image --}}
                                <img src="{{ $game['thumbnail'] }}" alt="Game Preview"
                                    class="w-full h-auto object-cover rounded-lg"> {{-- Made image fully responsive within its container --}}
                            </div>

                            <p class="text-gray-700 text-base leading-relaxed mb-4"> {{-- Changed text-md-start to text-base and increased mb --}}
                                {{ $game['description'] }}
                            </p>

                            <p class="text-green-700 text-sm text-base leading-relaxed mb-1"> {{-- Changed text-md-start to text-base and increased mb --}}
                                Requirements :
                            </p>
                            <p class="text-gray-700 text-sm text-base leading-relaxed mb-4"> {{-- Changed text-md-start to text-base and increased mb --}}
                                {{ $game['requirements'] }}
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
                            <p class="text-gray-700 text-sm text-base leading-relaxed mb-[60px]">
                                {{-- Changed text-md-start to text-base and increased mb --}}
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- transaction History model --}}

        <div id="transactionHistoryModal"
            class="fixed inset-0 z-50 {{ $hideModel }} overflow-hidden bg-black/60 flex items-center justify-center p-4"
            style="backdrop-filter: blur(2px);">
            {{-- The style for backdrop-filter provides a subtle blur to the background for a modern look --}}

            <div
                class="relative bg-white rounded-xl shadow-2xl w-300  mx-auto my-8 overflow-hidden h-[700px]  flex flex-col transform transition-all duration-300 scale-95 ">
                {{-- Increased max-h to 80vh for medium screens and up. Removed lg:max-h-50 as it's redundant/incorrect. --}}
                {{-- Width is now `w-full max-w-4xl` for better responsiveness from small to large screens. --}}

                <div
                    class="flex flex-wrap gap-4 md:gap-0 justify-between items-center p-5 border-b border-gray-200 bg-indigo-600 text-white">
                    {{-- Changed text-black to text-white for better contrast on indigo background, matching the original design intent. --}}
                    <h3 class="text-2xl font-semibold w-full md:w-auto">
                        Transaction History
                    </h3>
                    {{-- Added a close button icon to the header for better UX, matching previous design --}}
                    <div>
                        <div class="relative flex-grow w-full md:w-1/2 lg:w-full">
                            <div class="relative flex-grow">

                                <input wire:model.live = "search" type="text" id="transactionSearchInput"
                                    placeholder="Search transactions..."
                                    class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 placeholder-gray-400" />

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>

                                </div>

                                <button type="button" id="clearSearchButton"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center hidden"
                                    aria-label="Clear search">
                                    <svg class="h-5 w-5 text-gray-500 hover:text-gray-700" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="p-6 flex-grow overflow-y-auto custom-scrollbar">
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                        Transaction ID
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                        Amount
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                        Time Updated
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                        Details
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if (isset($historys))

                                    @foreach ($historys as $transaction)
                                        {{-- <p>come here</p>
                                            dd($transaction); --}}

                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-capitalize  font-medium text-gray-900 md:px-6"
                                                style="font-size: 11px; text-transform: capitalize;">
                                                <div class="relative flex items-center w-50 gap-2 group">
                                                    {{-- Abbreviated + upper-cased ID --}}
                                                    <span
                                                        class="font-semibold text-capitalize select-none tracking-wide">{{ $transaction['uuid'] }}</span>
                                                    <button
                                                        class="ml-2 text-xs text-blue-500 opacity-0 group-hover:opacity-100 transition"
                                                        onclick="copyToClipboard(this)"
                                                        data-id="{{ $transaction['uuid'] }}" title="Copy ID">
                                                        Copy
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm md:px-6">
                                                {{-- Conditional styling based on transaction type --}}
                                                @if ($transaction['type'] === 'deposit')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        CREDIT
                                                    </span>
                                                @elseif ($transaction['type'] === 'withdraw')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        WITHDRAW
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ $transaction['type'] }}
                                                    </span>
                                                @endif
                                            </td>
                                            @php
                                                $amount = number_format(abs($transaction['amount']) / 100, 2); // always positive, format to 2 decimals
                                                $isWithdraw = $transaction['type'] === 'withdraw';
                                            @endphp

                                            <td class="px-4 py-2 font-sm text-left">
                                                <span class="{{ $isWithdraw ? 'text-red-500' : 'text-green-600' }}">
                                                    {{ $isWithdraw ? '-' : '' }}${{ $amount }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 md:px-6">
                                                {{ \Carbon\Carbon::parse($transaction['updated_at'])->format('Y-m-d h:i A') }}
                                                {{-- Using Carbon for consistent date formatting --}}
                                            </td>
                                            {{-- <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 md:px-6">
                                                {{ Session::get('email') }}
                                            </td> --}}
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 md:px-6">
                                                @if ($transaction['status'] === 'COMPLETED')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        SUCCESS
                                                    </span>
                                                @elseif ($transaction['status'] === 'PENDING')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                        {{ $transaction['status'] }}
                                                    </span>
                                                @elseif ($transaction['status'] === 'FAILED')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        {{ $transaction['status'] }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        N/A
                                                    </span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 md:px-6 max-w-xs overflow-hidden text-ellipsis">
                                                @if ($transaction['type'] === 'deposit')
                                                    <div class="flex flex-col text-red-700 font-medium">
                                                        <span class="text-gray-700 text-xs font-medium">
                                                            {{ $transaction['game_name'] }}
                                                        </span>
                                                        <span class="text-xs text-gray-600 truncate w-52">
                                                            {{ $transaction['event_name'] }}
                                                        </span>
                                                    </div>
                                                @elseif ($transaction['type'] === 'withdraw')
                                                    <div class="flex flex-col text-red-700 font-medium">
                                                        <span>Withdraw to:</span>
                                                        <span class="text-xs text-gray-600 truncate w-52">
                                                            {{ Session::get('email') }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-500">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                {{-- Add a message if no transactions are available --}}
                                @if (count($historys) === 0)
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 text-lg">
                                            No transactions found.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>


                <div class="p-4 border-t border-gray-200 h-[75px] flex justify-between items-center">
                    @if (isset($historys))
                        {{ $historys->links() }}
                    @endif
                    <button id="closeModalBtnFooter" wire:click="$set('hideModel','hidden')"
                        onclick="document.body.classList.remove('overflow-hidden');
"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Close
                    </button>

                </div>
            </div>
        </div>
        {{-- in progress model --}}

        <div id="inProgressModel"
            class="fixed inset-0 z-50
             {{ $inProgressModel }} 
              overflow-hidden bg-black/60 flex items-center justify-center p-4"
            style="backdrop-filter: blur(2px);">
            <div
                class="relative bg-white rounded-xl shadow-2xl w-300  mx-auto my-8 overflow-hidden h-[700px]  flex flex-col transform transition-all duration-300 scale-95 ">


                <div class="flex justify-between items-center p-5 border-b border-gray-200 bg-indigo-600 text-white">
                    <h3 class="text-2xl font-semibold">
                        In progress :
                    </h3>
                </div>

                <div class="p-6 flex-grow overflow-y-auto custom-scrollbar">
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        @if (isset($progress))
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                            Game
                                        </th>

                                        {{-- <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                            Image
                                        </th> --}}
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:px-6">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($progress as $index => $game)
                                        @php
                                            // $eventCount = count($game['events']);
                                        @endphp
                                        {{-- @foreach ($game['events'] as $eventIndex => $event) --}}
                                        <tr>
                                            {{-- @if ($eventIndex === 0) --}}
                                            <td class="px-4 py-4 whitespace-nowrap text-sm md:px-6">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $game['name'] }}
                                                </span>
                                                <div class="mb-4 rounded-lg flex justify-center overflow-hidden ">

                                                    <img id="openModalipBtn-{{ $index }}"
                                                        data-index="{{ $index }}"
                                                        src="{{ $game['thumbnail'] }}" alt="Game Preview"
                                                        id="image1"
                                                        class="openModalipBtn w-50 h-auto object-cover rounded-lg">

                                                </div>
                                            </td>

                                            <td class="px-4 py-2 font-sm text-left">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-grey-800">
                                                    updated :{{ $game['date'] }}
                                                </span>
                                            </td>

                                        </tr>

                                        {{-- inprogress open model --}}

                                        <div id="jackpotModalip-{{ $index }}"
                                            class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 hidden z-100">
                                            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto overflow-hidden transform transition-all duration-300 scale-95 opacity-0"
                                                id="modalipContent-{{ $index }}">
                                                {{-- Modal Header --}}
                                                <div
                                                    class="flex justify-between items-center bg-gradient-to-r from-green-700 to-green-600 text-white px-6 py-4 rounded-t-xl shadow-md">
                                                    <h2 class="text-xl-center sm:text-2xl  font-bold">
                                                        {{ $game['title'] }}</h2>
                                                    {{-- Adjusted text size for smaller screens --}}
                                                    <button
                                                        class="closeModalipBtn text-white hover:text-gray-200 focus:outline-none transition-transform duration-200 transform hover:scale-110"
                                                        data-index="{{ $index }}">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                {{-- Modal Body --}}
                                                <div class="modal-body-scrollable  p-4 max-h-[85vh] overflow-y-auto">
                                                    {{-- Added max-h for scroll on smaller modals, overflow-y-auto --}}
                                                    <div
                                                        class="mb-4 rounded-lg flex justify-center overflow-hidden shadow-lg">
                                                        {{-- Centered image --}}
                                                        <img src="{{ $game['thumbnail'] }}" alt="Game Preview"
                                                            class="w-full h-auto object-cover rounded-lg">
                                                        {{-- Made image fully responsive within its container --}}
                                                    </div>

                                                    <p class="text-gray-700 text-base leading-relaxed mb-4">
                                                        {{-- Changed text-md-start to text-base and increased mb --}}
                                                        {{ $game['description'] }}
                                                    </p>

                                                    <p class="text-green-700 text-sm text-base leading-relaxed mb-1">
                                                        {{-- Changed text-md-start to text-base and increased mb --}}
                                                        Requirements :
                                                    </p>
                                                    <p class="text-gray-700 text-sm text-base leading-relaxed mb-4">
                                                        {{-- Changed text-md-start to text-base and increased mb --}}
                                                        {{ $game['requirements'] }}
                                                    </p>
                                                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                        Tasks
                                                    </h3>

                                                    {{-- example tasks --}}
                                                    @foreach ($game['events'] as $task)
                                                        @if ($task['status'] === 'completed')
                                                            <div class="space-y-[4px] mb-2"> {{-- Reduced mb for tighter spacing --}}
                                                                <div
                                                                    class="flex items-center justify-between bg-green-100 border p-3 rounded-lg shadow-sm">
                                                                    <svg class="w-5 h-5 text-green-400 mr-2"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    <span>{{ $task['name'] }}</span>
                                                                    <span
                                                                        class="text-green-600 font-semibold">{{ $task['points'] }}</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="space-y-[4px] mb-2"> {{-- Reduced mb for tighter spacing --}}
                                                                <div
                                                                    class="flex items-center justify-between bg-gray-50 border p-3 rounded-lg shadow-sm">
                                                                    <span>{{ $task['name'] }}</span>
                                                                    <span
                                                                        class="text-green-600 font-semibold">{{ $task['points'] }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                    <p class="text-red-700 text-sm text-base leading-relaxed mb-4">
                                                        {{-- Changed text-md-start to text-base and increased mb --}}
                                                        Disclaimer :
                                                    </p>
                                                    <p
                                                        class="text-gray-700 text-sm text-base leading-relaxed mb-[60px]">
                                                        {{-- Changed text-md-start to text-base and increased mb --}}
                                                        {{ $game['disclaimer'] }}
                                                    </p>
                                                    <div
                                                        class="absolute bottom-0 left-0 right-0 p-4 bg-transparent  shadow-lg">
                                                        {{-- This is the key change --}}

                                                        <button
                                                            class="btn-gradient w-full py-4 text-white font-bold text-lg rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                                                            <a href="{{ $game['play_url'] }}" target="_blank">PLAY
                                                                AND EARN
                                                                {{ $game['price'] }}</a>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if (count($games) === 0)
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 text-lg">
                                                No Inprogress Status found.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>

                            </table>
                        @endif
                    </div>

                </div>
                <div class="p-4 border-t border-gray-200 h-[75px] flex justify-end items-center">
                    <button id="closeModalBtnFooter" wire:click="$set('inProgressModel','hidden')"
                        onclick="document.body.classList.remove('overflow-hidden');
"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Close
                    </button>

                </div>

                {{-- ip pass --}}
                <input wire:model="UserIp" type="text" hidden value="" id="ipPass">


            </div>
        </div>





    </section>
</div>
</div>

<script>
    //search bar
    document.addEventListener('DOMContentLoaded', () => {
        let userip = "";
        fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => {
                userip = data.ip
                document.getElementById('ipPass').value = userip;
                console.log("Your IPv4 address is:", data.ip);


                const event = new Event('input', {
                    bubbles: true
                });
                document.getElementById('ipPass').dispatchEvent(event);
            });
        // --- Dropdown Functionality (from previous response, ensure it's still here) ---
        const dropdownButton = document.getElementById('options-menu');
        const dropdownPanel = dropdownButton.nextElementSibling;

        if (dropdownButton && dropdownPanel) {
            dropdownButton.addEventListener('click', () => {
                dropdownPanel.classList.toggle('hidden');
                dropdownPanel.classList.toggle('block');
                const expanded = dropdownButton.getAttribute('aria-expanded') === 'true' || false;
                dropdownButton.setAttribute('aria-expanded', !expanded);
            });

            document.addEventListener('click', (event) => {
                if (!dropdownButton.contains(event.target) && !dropdownPanel.contains(event.target)) {
                    dropdownPanel.classList.add('hidden');
                    dropdownPanel.classList.remove('block');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                }
            });

            dropdownPanel.querySelectorAll('a').forEach(item => {
                item.addEventListener('click', (event) => {
                    event.preventDefault();
                    const selectedText = item.textContent;
                    console.log('Selected filter:', selectedText);
                    dropdownButton.childNodes[0].nodeValue = selectedText;
                    dropdownPanel.classList.add('hidden');
                    dropdownPanel.classList.remove('block');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                    // In a real application, you would trigger your data filtering here
                });
            });
        }

        // --- Clear Button Functionality ---
        const searchInput = document.getElementById('transactionSearchInput');
        const clearButton = document.getElementById('clearSearchButton');

        if (searchInput && clearButton) {
            // Show/hide clear button based on input value
            searchInput.addEventListener('input', () => {
                if (searchInput.value.length > 0) {
                    clearButton.classList.remove('hidden');
                    clearButton.classList.add('block');
                } else {
                    clearButton.classList.add('hidden');
                    clearButton.classList.remove('block');
                }
            });

            // Clear input on button click
            clearButton.addEventListener('click', () => {
                searchInput.value = ''; // Clear the input field
                clearButton.classList.add('hidden'); // Hide the button
                clearButton.classList.remove('block');
                searchInput.focus(); // Optional: put focus back on the input
                // In a real application, you would trigger your data filtering/reset here
                // e.g., trigger a search with an empty string to show all results
            });

            // Initial check in case there's pre-filled text (though unlikely for a search bar)
            if (searchInput.value.length > 0) {
                clearButton.classList.remove('hidden');
                clearButton.classList.add('block');
            }
        }
    });
    //

    function copyToClipboard(btn) {
        const text = btn.dataset.id; // what we’re copying
        const original = btn.textContent; // save current label

        navigator.clipboard.writeText(text).then(() => {
            // 1) show “Copied!”
            btn.textContent = 'Copied!';
            // (optional) green text for feedback
            btn.classList.remove('text-blue-500');
            btn.classList.add('text-green-600');

            // 2) after 2 s, restore original label & color
            setTimeout(() => {
                btn.textContent = original;
                btn.classList.remove('text-green-600');
                btn.classList.add('text-blue-500');
            }, 2000);
        }).catch(err => {
            console.error('Clipboard copy failed:', err);
        });
    }
</script>

<script>
    // fetch data
    fetch('/Transactions')
        .then(res => res.json())
        .then(data => {
            console.log(data); // display the data
        });


    // copy button

    window.addEventListener('openHistoryModel', () => {
        // alert('ok');
        const pick = document.getElementById('transactionHistoryModal');
        console.log(pick);
        pick.classList.remove('hidden');

    })
    window.addEventListener('withdraw', () => {

        Swal.fire({
            text: "Your PayPal payment has been successfully sent. Check your mailbox for an email from 'Tremendous' which contains a link. Click that link then enter your PayPal email to receive your payment. ",
            icon: "success",
            draggable: true
        });

    })

    function confirmation() {

        const email = document.getElementById('paypalEmail1').value.trim();

        if (!(email)) {
            // alert('Please enter your PayPal email first ❗');
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please enter your PayPal email first ❗",
            });
            return;
        }
        Swal.fire({
            title: "Are you sure?",
            text: "Are you sure you want to do this? Your wallet and earnings will not be saved.",
            icon: "warning",
            showDenyButton: true,
            confirmButtonColor: "#008000",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            denyButtonText: `No`
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Email changed!",
                    icon: "success"

                });
                // alert('hi');
                document.getElementById('confirm_btn').click();
            }
        });


    }

    window.addEventListener('lowBalance', () => {
        Swal.fire({
            icon: "error",
            text: "The minimum withdraw amount is $5",
        });

    })
    window.addEventListener('limit', () => {

        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Can't update PayPal Email over 3 times",
            footer: '<a href="#">Why do I have this issue?</a>'
        });
    })
    window.addEventListener('alert', (event) => {
        let data = event.detail;

        Swal.fire({
            position: data.position,
            icon: data.type,
            title: data.title,
            showConfirmButton: false,
            timer: 3000
        });

    })

    window.addEventListener('refreshPage', () => {
        setTimeout(function() {
            location.reload();
        }, 1500);
    })


    window.addEventListener('mailLock', () => {
        // alert('tirm')
        const box = document.getElementById('btn');
        // alert(box);
        box.disabled = true;
    })


    //when button clicked
    window.addEventListener('model', () => {

        setTimeout(() => {

            const openButtons = document.querySelectorAll('.openModalBtn');
            const closeButtons = document.querySelectorAll('.closeModalBtn');
            var email = document.getElementById('paypalEmail');
            // console.log({{ $email }});
            /* ------- OPEN ------- */
            openButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // alert('clicked');
                    const email = document.getElementById('paypalEmail').value.trim();
                    const index = button.dataset.index;
                    const modal = document.getElementById(`jackpotModal-${index}`);
                    const modalContent = document.getElementById(
                        `modalContent-${index}`);
                    // var open = document.getElementById('successAlert');
                    var openModel = document.getElementById('openModel');


                    if (!(openModel.style.display == "block")) {
                        // alert('Please enter your PayPal email first ❗');

                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Please enter your PayPal email first ❗",
                        });
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
            closeButtons.forEach(button => {

                button.addEventListener('click', () => {
                    const index = button.dataset.index;
                    const modal = document.getElementById(`jackpotModal-${index}`);
                    const modalContent = document.getElementById(
                        `modalContent-${index}`);
                    document.body.classList.remove('overflow-hidden');

                    modalContent.classList.remove('scale-100', 'opacity-100');
                    modalContent.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => modal.classList.add('hidden'), 300);
                });
            })
        }, 1000);




    });

    //when button clicked
    window.addEventListener('model', () => {

        setTimeout(() => {

            const openButtons = document.querySelectorAll('.openModalipBtn');
            const closeButtons = document.querySelectorAll('.closeModalipBtn');
            var email = document.getElementById('paypalEmail');
            // console.log({{ $email }});
            /* ------- OPEN ------- */
            openButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // alert('clicked');
                    const email = document.getElementById('paypalEmail').value.trim();
                    const index = button.dataset.index;
                    const modal = document.getElementById(`jackpotModalip-${index}`);
                    const modalContent = document.getElementById(
                        `modalipContent-${index}`);
                    // var open = document.getElementById('successAlert');
                    var openModel = document.getElementById('openModel');


                    if (!(openModel.style.display == "block")) {
                        // alert('Please enter your PayPal email first ❗');

                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Please enter your PayPal email first ❗",
                        });
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
            closeButtons.forEach(button => {

                button.addEventListener('click', () => {
                    const index = button.dataset.index;
                    const modal = document.getElementById(`jackpotModalip-${index}`);
                    const modalContent = document.getElementById(
                        `modalipContent-${index}`);
                    document.body.classList.remove('overflow-hidden');

                    modalContent.classList.remove('scale-100', 'opacity-100');
                    modalContent.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => modal.classList.add('hidden'), 300);
                });
            })
        }, 1000);




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
