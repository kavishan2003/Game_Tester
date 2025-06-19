@extends('layouts.app')

@section('content')
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Update PayPal Email</h2>
                    <p class="text-gray-600 mb-4">Ensure your PayPal email is correct for smooth payouts.</p>
                    <form id="paypalForm" action="/paypal" method="post">
                        @csrf
                        <div class="relative mb-4">
                            <input type="email" placeholder="you@example.com" id="paypalEmail" name="email"
                                value="{{ Session::get('email') }}  "
                                class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 text-gray-700 text-lg"
                                aria-label="Enter your PayPal Email">
                        </div>

                        <button id="btn" type="submit"
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
            <h2 class="text-3xl flex items-center justify-center font-bold text-gray-900 mb-8 text-center">Available Games
                to Test</h2>

            <div class="text-gray-500 text-center rounded-xl p-5 ">
                @if (isset($request) && $request == 1)
                @else
                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.key') }}" data-theme="light">
                            {{-- or “dark” --}}
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                @endif

            </div>
        @endsection
