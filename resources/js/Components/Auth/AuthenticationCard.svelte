<script>
    import AuthenticationCardLogo from "@/Components/Auth/AuthenticationCardLogo.svelte";
</script>

<div class="flex items-center min-h-screen bg-gray-100 sm:justify-center">
    <div class="relative px-4 py-8 w-full bg-white shadow-lg sm:p-8 sm:max-w-sm sm:rounded-xl">
        <div class="absolute inset-x-0 -top-8 flex justify-center">
            <AuthenticationCardLogo/>
        </div>

        <div class="mt-8">
            <slot/>
        </div>
    </div>
</div>
