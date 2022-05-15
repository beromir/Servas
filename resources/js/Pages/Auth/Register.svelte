<script context="module">
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import {inertia, useForm} from '@inertiajs/inertia-svelte';
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.svelte'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.svelte'
    import JetButton from '@/Jetstream/Button.svelte'
    import JetInput from '@/Jetstream/Input.svelte'
    // import JetCheckbox from '@/Jetstream/Checkbox.svelte'
    import JetLabel from '@/Jetstream/Label.svelte'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.svelte'

    $title = 'Register';

    let form = useForm('Register', {
        name: null,
        email: null,
        password: null,
        password_confirmation: '',
        terms: false,
    });

    function register() {
        $form.post('/register', {
            onSuccess: () => $form.reset('password', 'password_confirmation'),
        });
    }
</script>

<JetAuthenticationCard>
    <JetAuthenticationCardLogo slot="logo"/>

    <JetValidationErrors class="mb-4"/>

    <form on:submit|preventDefault={register}>
        <div>
            <JetLabel id="name" label="Name"/>
            <JetInput id="name" class="mt-1 block w-full" bind:value={$form.name} required autofocus
                      autocomplete="name"/>
        </div>

        <div class="mt-4">
            <JetLabel id="email" label="Email"/>
            <JetInput id="email" type="email" class="mt-1 block w-full" bind:value={$form.email} required/>
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

        <!--
        <div class="mt-4" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
            <jet-label for="terms">
                <div class="flex items-center">
                    <jet-checkbox name="terms" id="terms" v-model:checked="form.terms"/>

                    <div class="ml-2">
                        I agree to the <a target="_blank" :href="route('terms.show')"
                                          class="underline text-sm text-gray-600 hover:text-gray-900">Terms of
                        Service</a> and <a target="_blank" :href="route('policy.show')"
                                           class="underline text-sm text-gray-600 hover:text-gray-900">Privacy
                        Policy</a>
                    </div>
                </div>
            </jet-label>
        </div>
        -->

        <div class="flex items-center justify-end mt-4">
            <a href="/login" use:inertia class="underline text-sm text-gray-600 hover:text-gray-900">
                Already registered?
            </a>

            <JetButton class="ml-4" type="submit">
                Register
            </JetButton>
        </div>
    </form>
</JetAuthenticationCard>
