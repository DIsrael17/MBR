<header x-data="{ open: false }" class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="text-lg font-semibold text-brand-700">{{ config('app.name') }}</a>

        <button type="button" class="rounded-md p-2 text-gray-600 hover:bg-gray-100 md:hidden"
                @click="open = ! open" :aria-expanded="open.toString()" aria-controls="menu-principal" aria-label="Abrir menú">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <nav id="menu-principal" aria-label="Principal"
             class="absolute inset-x-0 top-14 z-40 hidden border-b border-gray-200 bg-white md:static md:block md:border-0"
             :class="{ 'hidden': ! open }">
            <ul class="flex flex-col gap-1 p-4 md:flex-row md:items-center md:gap-6 md:p-0">
                <li><a href="{{ url('/') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-brand-700">Inicio</a></li>
            </ul>
        </nav>
    </div>
</header>
