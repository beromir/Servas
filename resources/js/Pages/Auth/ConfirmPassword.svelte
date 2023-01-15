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
    import JetValidationErrors from '@/Jetstream/ValidationErrors.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    $title = 'Secure Area';

    let form = useForm({
        password: '',
    });

    function submit() {
        $form.post(route('password.confirm'), {
            onFinish: () => $form.reset(),
        });
    }
</script>

<JetAuthenticationCard>
    <svelte:fragment slot="logo">
        <JetAuthenticationCardLogo/>
    </svelte:fragment>

    <div class="mb-4 text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <JetValidationErrors hasErrors={$form.hasErrors} class="mb-4"/>

    <form on:submit|preventDefault={submit}>
        <div>
            <JetLabel id="password" label="Password"/>
            <JetInput id="password" type="password" class="mt-1 block w-full" bind:value={$form.password} required
                      autocomplete="current-password" autofocus/>
        </div>

        <div class="flex justify-end mt-4">
            <JetButton class={['ml-4', $form.processing ? 'opacity-25' : ''].join(' ').trim()}
                       disabled={$form.processing} type="submit">
                Confirm
            </JetButton>
        </div>
    </form>
</JetAuthenticationCard>
