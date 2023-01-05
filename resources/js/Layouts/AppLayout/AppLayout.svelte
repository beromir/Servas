<script context="module">
    import {writable} from 'svelte/store';

    export const title = writable(null);
    export const showHeader = writable(true);
</script>

<script>
    import {inertia, page} from '@inertiajs/inertia-svelte';
    import LinkModal from "@/Partials/LinkModal.svelte";
    import DeleteLinkModal from "@/Partials/DeleteLinkModal.svelte";
    import {route, dispatchCustomEvent, clickOutside} from '@/utils';
    import DesktopMenuItem from "@/Components/Navigation/DesktopMenuItem.svelte";
    import DeleteTagModel from "@/Partials/DeleteTagModel.svelte";
    import CommandPalette from "@/Partials/CommandPalette.svelte";
    import Logo from "@/Components/Icons/Logo.svelte";
    import MobileMenuItem from "@/Components/Navigation/MobileMenuItem.svelte";
    import DeleteGroupModal from "@/Partials/DeleteGroupModal.svelte";

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

<div class="min-h-full">
    <nav class="bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href={route('home')} use:inertia on:click={() => showMobileMenu = false} class="mr-2.5">
                        <Logo class="h-8 w-8"/>
                    </a>
                    <div class="font-medium text-gray-50">
                        <a href={route('home')} use:inertia on:click={() => showMobileMenu = false}>{appName}</a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <DesktopMenuItem title="Home" url="/"/>
                            <DesktopMenuItem title="Links" url="/links" exactMatch={false}/>
                            <DesktopMenuItem title="Groups" url="/groups"/>
                            <DesktopMenuItem title="Tags" url="/tags"/>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <button on:click={() => dispatchCustomEvent('showCommandPalette')} title="Open search bar"
                                type="button"
                                class="bg-gray-800 p-1 rounded-full text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                            <span class="sr-only">Open search bar</span>
                            <!-- Heroicon name: outline/search -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>

                        <!-- Profile dropdown -->
                        <div class="ml-3 relative h-8 w-8">
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
                                     class="origin-top-right absolute right-0 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
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

                <button on:click={showCommandPaletteOnMobile} type="button"
                        class="ml-auto mr-2 bg-gray-800 flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white md:hidden">
                    <span class="sr-only">View notifications</span>
                    <!-- Heroicon name: outline/search -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </button>

                <div class="-mr-2 flex md:hidden">
                    <!-- Mobile menu button -->
                    <button on:click={() => showMobileMenu = !showMobileMenu} type="button"
                            class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>

                        {#if !showMobileMenu}
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>

                        {:else}
                            <svg class="block h-6 w-6"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        {/if}
                    </button>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            {#if showMobileMenu}
                <div class="md:hidden" id="mobile-menu">

                    <!-- Mobile main menu -->
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                        <MobileMenuItem title="Home" url="/" on:clicked={() => showMobileMenu = false}/>
                        <MobileMenuItem title="Links" url="/links" exactMatch={false}
                                        on:clicked={() => showMobileMenu = false}/>
                        <MobileMenuItem title="Groups" url="/groups" on:clicked={() => showMobileMenu = false}/>
                        <MobileMenuItem title="Tags" url="/tags" on:clicked={() => showMobileMenu = false}/>
                    </div>
                    <div class="pt-4 pb-3 border-t border-gray-700">
                        <div class="flex items-center px-5">
                            <button on:click={() => showMobileAccountMenu = !showMobileAccountMenu} type="button"
                                    class="flex-shrink-0 h-10 w-10 select-none">
                                <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </span>
                            </button>
                            <button on:click={() => showMobileAccountMenu = !showMobileAccountMenu} type="button"
                                    class="ml-3 overflow-x-hidden text-left select-none">
                                <div class="text-base font-medium leading-none text-white truncate">
                                    {$page.props.user.name}
                                </div>
                                <div class="text-sm font-medium leading-none text-gray-400 truncate">
                                    {$page.props.user.email}
                                </div>
                            </button>
                        </div>
                        {#if showMobileAccountMenu}
                            <div class="mt-3 px-2 space-y-1">
                                <a href={route('profile.show')} use:inertia on:click={() => showMobileMenu = false}
                                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Your
                                    Profile</a>
                                <a href={route('api-tokens.index')} use:inertia on:click={() => showMobileMenu = false}
                                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">
                                    API Tokens
                                </a>

                                <button use:inertia={{ href: route('logout'), method: 'post' }}
                                        on:click={() => showMobileMenu = false} type="button"
                                        class="block px-3 py-2 w-full rounded-md text-left text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">
                                    Log Out
                                </button>
                            </div>
                        {/if}
                    </div>
                </div>
            {/if}
        </div>
    </nav>

    {#if $showHeader}
        <header class="bg-white shadow">
            <div class="flex justify-between items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 sm:block lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    {$title}
                </h1>
                <button on:click={() => dispatchCustomEvent('prepareCreateNewLink')} type="button"
                        class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-white bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 sm:hidden">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </header>
    {/if}
    <main>
        <slot/>
    </main>
</div>

<LinkModal/>
<DeleteLinkModal/>
<DeleteTagModel/>
<DeleteGroupModal/>
<CommandPalette/>
