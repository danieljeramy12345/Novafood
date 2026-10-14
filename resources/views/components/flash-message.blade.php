@if(session('success'))
    <div id="flash-success" class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-start gap-3 transition-all duration-300">
        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div class="flex-1 text-sm font-medium text-emerald-800 pt-1">
            {{ session('success') }}
        </div>
        <button type="button" onclick="document.getElementById('flash-success').remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
@endif

@if(session('error'))
    <div id="flash-error" class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 shadow-sm flex items-start gap-3 transition-all duration-300">
        <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="flex-1 text-sm font-medium text-rose-800 pt-1">
            {{ session('error') }}
        </div>
        <button type="button" onclick="document.getElementById('flash-error').remove()" class="text-rose-500 hover:text-rose-700 p-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
@endif

@if(session('info'))
    <div id="flash-info" class="mb-6 rounded-2xl bg-blue-50 border border-blue-200 p-4 shadow-sm flex items-start gap-3 transition-all duration-300">
        <div class="w-8 h-8 rounded-xl bg-blue-500 text-white flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="flex-1 text-sm font-medium text-blue-800 pt-1">
            {{ session('info') }}
        </div>
        <button type="button" onclick="document.getElementById('flash-info').remove()" class="text-blue-500 hover:text-blue-700 p-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-4 shadow-sm">
        <div class="flex items-center gap-2 text-amber-800 font-semibold text-sm mb-1">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Please check the following items:
        </div>
        <ul class="list-disc list-inside text-xs font-medium text-amber-700 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
