<div
    x-data="updateAvailable"
    x-cloak
    x-show="isAvailable"
>
    <x-dropdown.dropdown
        anchor="end"
        offsetY="10px"
    >
        <x-slot:trigger
            {{ $attributes->twMergeFor('trigger', 'size-6 max-lg:size-10 max-lg:border max-lg:dark:bg-white/[3%]') }}
            size="none"
        >
            <x-button
                class="gap-2.5 text-[#56462E] shadow-none dark:text-foreground"
                ::href="route"
                @click.prevent="isVersionUpdateAvailable ? window.location.href = $el.href : null"
                variant="none"
            >
                {{ __('Update Available') }}
                <x-tabler-circle-chevron-up stroke-width="1.5" />
            </x-button>
        </x-slot:trigger>
        <x-slot:dropdown
            class="max-h-[40vh] overflow-hidden overflow-y-auto"
            x-bind:class="{ 'hidden': isVersionUpdateAvailable }"
        >
            <template x-for="item in updateAvailableExtensions">
                <a
                    class="flex items-center gap-2 border-b px-3 py-2 text-heading-foreground transition-colors last:border-b-0 hover:bg-foreground/5 hover:no-underline"
                    rel="alternate"
                    :href="'/dashboard/admin/marketplace/' + item.slug"
                >
                    <span x-text="item.name"></span>
                </a>
            </template>
        </x-slot:dropdown>
    </x-dropdown.dropdown>
</div>

@push('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('updateAvailable', () => ({
                route: '',
                isAvailable: false,
                isVersionUpdateAvailable: false,
                isExtensionUpdateAvailable: false,
                updateAvailableExtensions: [],
                init() {
                    this.checkAvailability();
                },
                async checkAvailability() {
                    const res = await fetch('{{ route('dashboard.user.check.update-available') }}');

                    if (!res.ok) {
                        console.error('Network error: check update availablity');
                        return;
                    }

                    const resData = await res.json();

                    this.isVersionUpdateAvailable = resData.versionUpdateAvailable;
                    this.isExtensionUpdateAvailable = resData.extensionUpdateAvailable;
                    this.updateAvailableExtensions = resData.updateAvailableExtensions;
                    this.isAvailable = this.isVersionUpdateAvailable || this
                        .isExtensionUpdateAvailable;

                    if (this.isVersionUpdateAvailable) {
                        this.route = "{{ route('dashboard.admin.update.index') }}";
                    } else if (this.isExtensionUpdateAvailable) {
                        this.route = "{{ route('dashboard.admin.marketplace.index') }}";
                    }
                }
            }));
        })
    </script>
@endpush
