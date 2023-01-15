<script context="module">
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.svelte';
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetInput from '@/Jetstream/Input.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {tick} from 'svelte';
    import {route} from "@/utils";

    let recovery = false;
    let recoveryCodeInput;
    let codeInput;

    let form = useForm({
        code: '',
        recovery_code: '',
    });

    $title = 'Two-factor Confirmation';

    async function toggleRecovery() {
        recovery = !recovery;

        await tick();

        if (recovery) {
            recoveryCodeInput.focus();
            $form.code = '';
        } else {
            codeInput.focus();
            $form.recovery_code = '';
        }
    }

    function submit() {
        $form.post(route('two-factor.login'));
    }
</script>

<JetAuthenticationCard>
    <svelte:fragment slot="logo">
        <JetAuthenticationCardLogo/>
    </svelte:fragment>

    <div class="mb-4 text-sm text-gray-600">
        {#if !recovery}
            Please confirm access to your account by entering the authentication code provided by your authenticator
            application.

        {:else}
            Please confirm access to your account by entering one of your emergency recovery codes.
        {/if}
    </div>

    <form on:submit|preventDefault={submit}>
        {#if !recovery}
            <JetLabel id="code" label="Code"/>
            <JetInput bind:this={codeInput} id="code" type="text" inputmode="numeric" class="mt-1 block w-full"
                      bind:value={$form.code} autofocus autocomplete="one-time-code"/>
            <JetInputError message={$form.errors.code}/>

        {:else}
            <JetLabel id="recovery_code" label="Recovery Code"/>
            <JetInput bind:this={recoveryCodeInput} id="recovery_code" type="text" class="mt-1 block w-full"
                      bind:value={$form.recovery_code} autocomplete="one-time-code"/>
            <JetInputError message={$form.errors.code}/>
        {/if}

        <div class="flex items-center justify-end mt-4">
            <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                    on:click|preventDefault={toggleRecovery}>
                {#if !recovery}
                    Use a recovery code

                {:else}
                    Use an authentication code
                {/if}
            </button>

            <JetButton class={['ml-4', $form.processing ? 'opacity-25' : ''].join(' ').trim()}
                       disabled={$form.processing} type="submit">
                Log in
            </JetButton>
        </div>
    </form>
</JetAuthenticationCard>
