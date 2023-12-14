<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.svelte'
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";

    export let tokens;
    export let availablePermissions;
    export let defaultPermissions;

    $title = 'API Tokens';
</script>

<Main title="API Tokens">
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <ApiTokenManager {tokens} {availablePermissions} {defaultPermissions}/>
        </div>
    </div>
</Main>
