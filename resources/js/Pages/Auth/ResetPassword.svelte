<script module>
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import { preventDefault } from 'svelte/legacy';

    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.svelte';
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetInput from '@/Jetstream/Input.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import JetValidationErrors from '@/Jetstream/ValidationErrors.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    /**
     * @typedef {Object} Props
     * @property {string} [email]
     * @property {string} [token]
     */

    /** @type {Props} */
    let { email = '', token = '' } = $props();

    let form = useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    });

    $title = 'Reset Password';

    function submit() {
        $form.post(route('password.update'), {
            onFinish: () => $form.reset('password', 'password_confirmation'),
        });
    }
</script>

<JetAuthenticationCard>
    {#snippet logo()}
    
            <JetAuthenticationCardLogo/>
        
    {/snippet}

    <JetValidationErrors hasErrors={$form.hasErrors} class="mb-4"/>

    <form onsubmit={preventDefault(submit)}>
        <div>
            <JetLabel id="email" label="Email"/>
            <JetInput id="email" type="email" class="mt-1 block w-full" bind:value={$form.email} required autofocus/>
        </div>

        <div class="mt-4">
            <JetLabel id="password" label="Password"/>
            <JetInput id="password" type="password" class="mt-1 block w-full" bind:value={$form.password} required
                      autocomplete="new-password"/>
        </div>

        <div class="mt-4">
            <JetLabel id="password_confirmation" label="Confirm Password"/>
            <JetInput id="password_confirmation" type="password" class="mt-1 block w-full"
                      bind:value={$form.password_confirmation} required autocomplete="new-password"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <JetButton class={$form.processing ? 'opacity-25' : ''} disabled={$form.processing} type="submit">
                Reset Password
            </JetButton>
        </div>
    </form>
</JetAuthenticationCard>
