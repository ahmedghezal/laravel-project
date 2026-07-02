<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} - Connect & Share</title>
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
                50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
            }
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            .glow-button {
                animation: pulse-glow 3s ease-in-out infinite;
            }
            .gradient-text {
                background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .blob {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.5;
            }
        </style>
    </head>
    <body class="bg-slate-950 text-white antialiased overflow-x-hidden">
        <!-- Background Blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="blob w-96 h-96 bg-indigo-600 top-20 -left-48"></div>
            <div class="blob w-80 h-80 bg-purple-600 top-40 right-0"></div>
            <div class="blob w-72 h-72 bg-pink-600 bottom-20 left-1/3"></div>
        </div>

        <div class="min-h-screen relative z-10">
            <!-- Navigation -->
            <nav class="px-6 py-6">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">Connect Hub</span>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300 font-medium">Dashboard</a>
                            <a href="{{ route('posts.index') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 transition-all duration-300 font-medium glow-button">Explore Posts</a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300 font-medium">Log in</a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 transition-all duration-300 font-medium glow-button">Get Started</a>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <main class="px-6 py-16">
                <div class="max-w-7xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <!-- Left Content -->
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                <span class="text-sm text-slate-300">Welcome to your first internship project! 🚀</span>
                            </div>
                            
                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold leading-tight">
                                Share Your <br>
                                <span class="gradient-text">Ideas & Stories</span>
                            </h1>
                            
                            <p class="text-xl text-slate-400 max-w-lg leading-relaxed">
                                A beautiful platform where you can connect with others, share your thoughts, and engage in meaningful conversations.
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                @auth
                                    <a href="{{ route('posts.index') }}" class="group px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 transition-all duration-300 font-semibold flex items-center justify-center gap-2 glow-button">
                                        Start Posting
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="px-8 py-4 rounded-2xl border border-white/10 hover:bg-white/5 transition-all duration-300 font-semibold">
                                        View Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="group px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 transition-all duration-300 font-semibold flex items-center justify-center gap-2 glow-button">
                                        Join Free
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl border border-white/10 hover:bg-white/5 transition-all duration-300 font-semibold">
                                        Sign In
                                    </a>
                                @endauth
                            </div>
                            
                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-8 pt-8 border-t border-white/10">
                                <div>
                                    <div class="text-3xl font-bold gradient-text">100%</div>
                                    <div class="text-sm text-slate-400">Handcrafted</div>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold gradient-text">∞</div>
                                    <div class="text-sm text-slate-400">Posts</div>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold gradient-text">24/7</div>
                                    <div class="text-sm text-slate-400">Available</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Content - Illustration -->
                        <div class="relative">
                            <div class="float-animation">
                                <div class="relative">
                                    <!-- Main Card -->
                                    <div class="bg-slate-900/80 backdrop-blur-xl rounded-3xl p-8 border border-white/10 shadow-2xl">
                                        <div class="flex items-center gap-4 mb-6">
                                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-lg">Welcome Aboard!</div>
                                                <div class="text-slate-400 text-sm">Start your journey today</div>
                                            </div>
                                        </div>
                                        
                                        <!-- Post Preview -->
                                        <div class="bg-slate-800/50 rounded-2xl p-6 mb-4">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-orange-400"></div>
                                                <div>
                                                    <div class="font-medium">Developer</div>
                                                    <div class="text-xs text-slate-400">Just now</div>
                                                </div>
                                            </div>
                                            <p class="text-slate-300">Just created my first post! This platform looks amazing. 🌟</p>
                                        </div>
                                        
                                        <!-- Interaction Buttons -->
                                        <div class="flex gap-3">
                                            <button class="flex-1 px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                                                </svg>
                                                <span class="text-sm">Like</span>
                                            </button>
                                            <button class="flex-1 px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span class="text-sm">Comment</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Floating Elements -->
                                    <div class="absolute -top-4 -right-4 w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute -bottom-4 -left-4 w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Features Section -->
                    <div class="mt-32">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Why Choose ConnectHub?</h2>
                            <p class="text-slate-400 text-lg">Everything you need to share and connect</p>
                        </div>
                        
                        <div class="grid md:grid-cols-3 gap-8">
                            <div class="group bg-slate-900/50 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-2">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold mb-3">Easy Posting</h3>
                                <p class="text-slate-400">Share your thoughts with a beautiful, intuitive interface designed for everyone.</p>
                            </div>
                            
                            <div class="group bg-slate-900/50 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-purple-500/50 transition-all duration-300 hover:-translate-y-2">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold mb-3">Engage & Discuss</h3>
                                <p class="text-slate-400">Comment on posts, reply to others, and build meaningful connections.</p>
                            </div>
                            
                            <div class="group bg-slate-900/50 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-pink-500/50 transition-all duration-300 hover:-translate-y-2">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold mb-3">Secure & Safe</h3>
                                <p class="text-slate-400">Built with security in mind, so you can focus on what matters.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="px-6 py-12 mt-20 border-t border-white/10">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">Connect Hub</span>
                    </div>
                    <p class="text-slate-500 text-sm">© 2024 Connect Hub. My first internship project! 🎉</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
