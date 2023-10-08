<script context="module">
    import {writable} from 'svelte/store';

    export const title = writable(null);
    export const showHeader = writable(true);
</script>

<script>
    import {inertia, Link, page} from '@inertiajs/svelte';
    import LinkModal from "@/Partials/LinkModal.svelte";
    import DeleteLinkModal from "@/Partials/DeleteLinkModal.svelte";
    import {route, dispatchCustomEvent, clickOutside} from '@/utils';
    import DesktopMenuItem from "@/Components/Navigation/DesktopMenuItem.svelte";
    import DeleteTagModel from "@/Partials/DeleteTagModel.svelte";
    import CommandPalette from "@/Partials/CommandPalette.svelte";
    import Logo from "@/Components/Icons/Logo.svelte";
    import MobileMenuItem from "@/Components/Navigation/MobileMenuItem.svelte";
    import DeleteGroupModal from "@/Partials/DeleteGroupModal.svelte";
    import TagSelectMenu from "@/Partials/TagSelectMenu.svelte";

    const appName = $page.props.appName;

    let showMobileMenu = false;
    let showProfileDropdown = false;
    let showMobileAccountMenu = false;

    $: if (showMobileMenu) showMobileAccountMenu = false;

    const showCommandPaletteOnMobile = () => {
        dispatchCustomEvent('showCommandPalette');
        showMobileMenu = false;
    };

    function handleKeydown(event) {
        if ((event.key === 'f') && (event.target.tagName !== 'INPUT')) {
            if (event.metaKey || event.altKey || event.shiftKey || event.ctrlKey) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            dispatchCustomEvent('showCommandPalette');
        }
    }
</script>

<svelte:head>
    <title>{$title ? `${$title} | ${appName}` : appName}</title>
</svelte:head>

<svelte:window on:keydown={handleKeydown}/>

<div class="flex min-h-full">
    <div class="sticky top-0 flex-none py-2 pl-2 w-[280px] h-screen">
        <div class="flex flex-col justify-between p-4 w-full h-full bg-gray-800 rounded-xl shadow">
            <div class="flex items-center">
                <!-- Logo -->
                <Link href={route('home')} on:click={() => showMobileMenu = false} class="mr-2.5">
                    <Logo class="h-8 w-8"/>
                </Link>
                <div class="font-medium text-gray-50">
                    <Link href={route('home')} on:click={() => showMobileMenu = false}>{appName}</Link>
                </div>

                <!-- Search button -->
                <button on:click={() => dispatchCustomEvent('showCommandPalette')} title="Open search bar"
                        type="button"
                        class="ml-auto bg-gray-800 p-1 rounded-full text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                    <span class="sr-only">Open search bar</span>
                    <!-- Heroicon name: outline/search -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>

            <!-- Profile dropdown -->
            <div class="relative h-8 w-8">
                <button on:click={() => showProfileDropdown = !showProfileDropdown} type="button"
                        class="max-w-xs h-8 w-8 rounded-full overflow-hidden bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="sr-only">Open user menu</span>
                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                {#if showProfileDropdown}
                    <div use:clickOutside on:click_outside={() => showProfileDropdown = false}
                         class="origin-bottom-left absolute bottom-0 left-full ml-3 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                         role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                         tabindex="-1">

                        <a href={route('profile.show')} use:inertia
                           on:click={() => showProfileDropdown = false}
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                           role="menuitem" tabindex="-1"
                           id="user-menu-item-0">Your Profile</a>

                        <a href={route('api-tokens.index')} use:inertia
                           on:click={() => showProfileDropdown = false}
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                           role="menuitem" tabindex="-1"
                           id="user-menu-item-1">API Tokens</a>

                        <button use:inertia={{ href: route('logout'), method: 'post' }}
                                on:click={() => showProfileDropdown = false}
                                type="button"
                                class="block px-4 py-2 w-full text-left text-sm text-gray-700 hover:bg-gray-50"
                                role="menuitem" tabindex="-1" id="user-menu-item-2">
                            Log Out
                        </button>
                    </div>
                {/if}
            </div>
        </div>
    </div>

    <main class="flex-1">
        <slot/>
    </main>
</div>

<LinkModal/>
<DeleteLinkModal/>
<DeleteTagModel/>
<DeleteGroupModal/>
<CommandPalette/>
<TagSelectMenu/>
