<script>
    import Button from "@/Components/Buttons/Button.svelte";
    import {dispatchCustomEvent} from "@/utils";
</script>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

    <div class="px-4 w-full sm:flex justify-between sm:px-0">
        <slot name="toolbar">
            <div></div>
        </slot>

        <div class="hidden space-x-3 sm:inline-flex">
            <Button on:clicked="{() => dispatchCustomEvent('prepareCreateNewLink')}" title="new link"
                    class="focus:ring-offset-gray-100">
                <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                          clip-rule="evenodd"/>
                </svg>
            </Button>
        </div>
    </div>

    <div class="py-6">
        <!-- Main content -->
        <slot/>
    </div>
</div>
