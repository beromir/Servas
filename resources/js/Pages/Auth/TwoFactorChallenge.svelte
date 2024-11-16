<script context="module">
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import AuthenticationCard from "@/Components/Auth/AuthenticationCard.svelte";
    import {useForm} from "@inertiajs/svelte";
    import {tick} from 'svelte';
    import {route} from "@/utils";
    import Text from "@/Components/FormLayouts/Inputs/Text.svelte";
    import SubmitButton from "@/Components/Auth/SubmitButton.svelte";

    let recovery = false;

    let form = useForm({
        code: '',
        recovery_code: '',
    });

    $title = 'Two-factor Confirmation';

    async function toggleRecovery() {
        recovery = !recovery;

        await tick();

        if (recovery) {
            $form.code = '';
        } else {
            $form.recovery_code = '';
        }
    }

    function submit() {
        $form.post(route('two-factor.login'));
    }
</script>

<AuthenticationCard>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-200">
        {#if !recovery}
            Please confirm access to your account by entering the authentication code provided by your authenticator
            application.

        {:else}
            Please confirm access to your account by entering one of your emergency recovery codes.
        {/if}
    </div>

    <form on:submit|preventDefault={submit}>
        {#if !recovery}
            <Text name="code" label="Code" bind:value={$form.code} error={$form.errors.code}
                  inputmode="numeric" autofocus autocomplete="one-time-code"/>

        {:else}

            <Text name="recovery_code" label="Recovery Code"
                  bind:value={$form.recovery_code} error={$form.errors.recovery_code} autofocus
                  autocomplete="one-time-code"/>
        {/if}

        <div class="flex justify-end mt-2">
            <button type="button" class="text-sm text-gray-600 cursor-pointer hover:text-gray-800 dark:text-gray-200 dark:hover:text-white"
                    on:click|preventDefault={toggleRecovery}>
                {#if !recovery}
                    Use a recovery code

                {:else}
                    Use an authentication code
                {/if}
            </button>
        </div>

        <SubmitButton title="Confirm" class="mt-12"/>
    </form>
</AuthenticationCard>
