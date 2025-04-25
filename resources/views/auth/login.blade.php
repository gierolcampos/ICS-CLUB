@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#FF0000] via-[#B22222] to-[#8B0000] relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Grid Pattern -->
        <div class="absolute top-0 left-0 w-full h-full opacity-5">
            <div class="grid grid-cols-12 gap-4 h-full">
                @for($i = 0; $i < 12; $i++)
                    <div class="border-r border-white/10"></div>
                @endfor
            </div>
        </div>
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="grid grid-rows-12 gap-4 h-full">
                @for($i = 0; $i < 12; $i++)
                    <div class="border-b border-white/10"></div>
                @endfor
            </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-red-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-red-600/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-3/4 left-1/3 w-48 h-48 bg-red-400/20 rounded-full blur-2xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <!-- Animated Circles -->
        <div class="absolute top-1/3 right-1/4 w-32 h-32 border-4 border-red-500/30 rounded-full animate-spin-slow"></div>
        <div class="absolute bottom-1/3 left-1/4 w-24 h-24 border-4 border-red-400/30 rounded-full animate-spin-slow-reverse"></div>
    </div>

    <div class="max-w-md w-full mx-4 relative z-10">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-white/20 transform hover:scale-105 transition-transform duration-300">
                    <svg class="w-16 h-16 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
            </div>
            <h2 class="text-4xl font-bold text-white tracking-tight">ICS Portal</h2>
            <p class="text-white/80 mt-2 text-lg">Integrated Computer Society</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-900/50 backdrop-blur-sm border border-red-500/50 p-4 mb-6 rounded-lg transform hover:scale-[1.02] transition-transform duration-300">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-200">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-white/20 transform hover:scale-[1.02] transition-transform duration-300">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-white">Email address</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-white/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required 
                                class="block w-full pl-10 pr-3 py-2 bg-white/10 border border-white/20 text-white placeholder-white/50 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Enter your email">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-white">Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-white/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="block w-full pl-10 pr-3 py-2 bg-white/10 border border-white/20 text-white placeholder-white/50 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Enter your password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-red-500 focus:ring-red-500 border-white/20 rounded bg-white/10">
                        <label for="remember" class="ml-2 block text-sm text-white">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out transform hover:scale-[1.02]">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Access Portal
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-transparent text-white/50">
                            Integrated Computer Society
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes spin-slow-reverse {
        from { transform: rotate(360deg); }
        to { transform: rotate(0deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 20s linear infinite;
    }
    .animate-spin-slow-reverse {
        animation: spin-slow-reverse 20s linear infinite;
    }
</style>
@endsection 