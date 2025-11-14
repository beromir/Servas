<script module>
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.svelte';
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import JetValidationErrors from '@/Jetstream/ValidationErrors.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import Input from "@/Components/Input.svelte";

    let {email = '', token = ''} = $props();

    let form = useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    });

    $title = 'Reset Password';

    function submit(event) {
        event.preventDefault();

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

    <form onsubmit={submit}>
        <div>
            <JetLabel id="email" label="Email"/>
            <Input id="email" type="email" required autofocus bind:value={$form.email} class="mt-1"/>
        </div>

        <div class="mt-4">
            <JetLabel id="password" label="Password"/>
            <Input id="password" type="password" required autocomplete="new-password" bind:value={$form.password}
                   class="mt-1"/>
        </div>

        <div class="mt-4">
            <JetLabel id="password_confirmation" label="Confirm Password"/>
            <Input id="password_confirmation" type="password" required autocomplete="new-password"
                   bind:value={$form.password_confirmation} class="mt-1"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <JetButton class={$form.processing ? 'opacity-25' : ''} disabled={$form.processing} type="submit">
                Reset Password
            </JetButton>
        </div>
    </form>
</JetAuthenticationCard>
