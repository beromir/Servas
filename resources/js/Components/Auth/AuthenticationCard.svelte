<script>
    import AuthenticationCardLogo from "@/Components/Auth/AuthenticationCardLogo.svelte";
</script>

<div class="flex items-center min-h-screen bg-gray-200 sm:justify-center dark:bg-gray-900">
    <div class="relative px-4 py-8 w-full bg-white shadow-lg ring-contrast sm:p-8 sm:max-w-sm sm:rounded-xl dark:bg-gray-800">
        <div class="absolute inset-x-0 -top-8 flex justify-center">
            <AuthenticationCardLogo/>
        </div>

        <div class="mt-8">
            <slot/>
        </div>
    </div>
</div>
