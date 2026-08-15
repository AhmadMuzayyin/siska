<div>
    <div class="flex items-center justify-between gap-2 p-1.5 rounded-xl bg-zinc-100/80 dark:bg-zinc-800/80 border border-zinc-200/70 dark:border-zinc-700/50">
        <div class="flex items-center gap-2.5 min-w-0 flex-1">
            <flux:avatar
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                class="size-8 shrink-0"
            />
            <div class="grid flex-1 min-w-0 leading-tight">
                <span class="truncate text-xs font-bold text-zinc-900 dark:text-zinc-100">
                    {{ auth()->user()->name }}
                </span>
                <span class="truncate text-[10px] text-zinc-500 dark:text-zinc-400">
                    {{ auth()->user()->email }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="shrink-0">
            @csrf
            <button
                type="submit"
                class="flex size-8 items-center justify-center rounded-lg text-zinc-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 dark:hover:text-rose-400 transition-colors cursor-pointer"
                title="{{ __('Log out') }}"
                data-test="logout-button"
                aria-label="{{ __('Log out') }}"
            >
                <flux:icon name="arrow-right-start-on-rectangle" class="size-4" />
                <span class="sr-only">{{ __('Log out') }}</span>
            </button>
        </form>
    </div>
</div>
