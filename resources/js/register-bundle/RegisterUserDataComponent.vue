<template>
    <div>
        <ValidationObserver ref="observer" v-slot="{ valid }">
             <b-field :label="$t('firstname')">
                <ValidationProvider v-slot="{ errors }" name="firstname" rules="required">
                    <b-input v-model="formdata.firstname" :placeholder="$t('firstname')" required></b-input>
                    <p class="help is-danger">{{ errors[0] }}</p>
                </ValidationProvider>
            </b-field>

            <b-field :label="$t('lastname')">
                <ValidationProvider v-slot="{ errors }" name="lastname" rules="required">
                    <b-input v-model="formdata.lastname" :placeholder="$t('lastname')" required></b-input>
                    <p class="help is-danger">{{ errors[0] }}</p>
                </ValidationProvider>
            </b-field>

            <b-field :label="$t('Gender')">
                <ValidationProvider v-slot="{ errors }" name="gender" rules="required">
                    <b-radio v-model="formdata.gender" native-value="0"> {{ $t('Other / Rather not say') }}</b-radio>
                    <b-radio v-model="formdata.gender" native-value="1"> {{ $t('Female') }}</b-radio>
                    <b-radio v-model="formdata.gender" native-value="2"> {{ $t('Male') }}</b-radio>
                    <p class="help is-danger">{{ errors[0] }}</p>
                </ValidationProvider>
            </b-field>

            <b-field :label="$t('email')">
                <ValidationProvider v-slot="{ errors }" name="email" rules="required|email">
                    <b-input v-model="formdata.email" type="email" :placeholder="$t('email')" required></b-input>
                    <p class="help is-danger">{{ errors[0] }}</p>
                </ValidationProvider>
            </b-field>

            <b-field :label="$t('iddocument')">
                <ValidationProvider v-slot="{ errors }" name="ID Number" rules="required">
                    <b-input v-model="formdata.idnumber" maxlength="12" required></b-input>
                    <p class="help is-danger">{{ errors[0] }}</p>
                </ValidationProvider>
            </b-field>

            <b-field :label="$t('password')">
                <ValidationProvider v-slot="{ errors }" name="password" rules="required|min:6">
                    <b-input v-model="formdata.password" type="password" password-reveal></b-input>
                    <p class="help is-danger">{{ errors[0] }}</p>
                </ValidationProvider>
            </b-field>

            <b-button type="is-primary" @click="validateBeforeSubmit()">{{ $t("next") }}</b-button>
        </ValidationObserver>
    </div>
</template>

<script>
import { store } from "./store.js";
import { EventBus } from "./eventBus.js";
import { ValidationObserver } from "vee-validate";

export default {
    components: {
        ValidationObserver,
    },
    props: ['checkemailunicity'],

    data() {
        return {
            errors: [],
            formdata: {
                firstname: null,
                lastname: null,
                email: null,
                password: null,
                idnumber_type: "passport",
                idnumber: null,
                address: null,
                phonenumber: null,
                tc_consent: false,
            },
        };
    },

    mounted() {},

    methods: {
        async validateBeforeSubmit() {
            const isValid = await this.$refs.observer.validate();

            if (isValid) {
                if (this.checkemailunicity) {
                    await this.checkEmailUnicity();
                } else {
                    this.updateData();
                }
            } else {
                this.$buefy.toast.open({
                    message: this.$t('The form is invalid, please check the fields marked in red and try again'),
                    type: "is-danger",
                    position: "is-bottom",
                });
            }
        },

        async checkEmailUnicity() {
            const isValid = await axios
                .post("/api/checkemail", {
                    email: this.formdata.email,
                })
                .then(response => {
                    if (response.status === 204) {
                        return true;
                    }
                })
                .catch(err => {
                    if (err.status === 409) {
                        return false;
                    }
                });

            if (isValid) {
                this.updateData();
            } else {
                this.$buefy.toast.open({
                    message: this.$t('An account with this email already exists.'),
                    type: "is-danger",
                    position: "is-bottom",
                });
            }
        },

        updateData() {
            store.updateUserData(this.formdata);
            EventBus.$emit("moveToNextStep");
        },
    },
};
</script>
