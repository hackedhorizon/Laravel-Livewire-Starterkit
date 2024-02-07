<div>
    @if (session('message'))
        <div class="text-white bg-green-500">
            {{ session('message') }}
            Welcome, {{ Auth::user()->name }}<br>
        </div>
    @endif
</div>
