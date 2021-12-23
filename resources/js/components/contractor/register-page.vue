<template>
    <b-container class="mt-4 pb-4">
        <h2>Для завершения регистрации заполните поля</h2>
        <b-row class="mt-3">
            <b-col cols="6">

                <b-form-group class="mb-3" label="ИНН">
                    <b-form-input
                        v-model="userInn"
                        style="width: 75%"
                    ></b-form-input>
                </b-form-group>

                <b-form-group class="mb-3" label="Имя">
                    <b-form-input
                        v-model="userName"
                        style="width: 75%"
                    ></b-form-input>
                </b-form-group>

                <b-form-group class="mb-3" label="Фамилия">
                    <b-form-input
                        v-model="userLastName"
                        style="width: 75%"
                    ></b-form-input>
                </b-form-group>

                <b-form-group class="mb-3" label="Отчество">
                    <b-form-input
                        v-model="userMiddleName"
                        style="width: 75%"
                    ></b-form-input>
                </b-form-group>

                <b-form-group class="mb-3" label="Email">
                    <b-form-input
                        v-model="userEmail"
                        style="width: 75%"
                    ></b-form-input>
                </b-form-group>

                <b-row v-show="isRegError">
                    <h4 class="errors-header">При регистрации возникли ошибки:</h4>
                    <ul>
                        <li v-for="error in regErrors" :key="error">
                            {{ error }}
                        </li>
                    </ul>
                </b-row>

                <hr>

                <b-form-group class="form-check">
                    <input class="form-check-input" type="checkbox" v-model="oldEnoughCheckbox" id="contractor_confirm_18_yo">
                    <label class="form-check-label" for="contractor_confirm_18_yo">
                        Я подтверждаю, что мне исполнилось 18 лет
                    </label>
                </b-form-group>

                <b-form-group class="form-check">
                    <input class="form-check-input" type="checkbox" v-model="readedTOS" id="contractor_confirm_private_policy">
                    <label class="form-check-label" for="contractor_confirm_private_policy">
                        Я ознакомлен и согласен:<br>
                        - <a href="/files/rules.pdf" target="_blank">с правилами пользования электронной
                        площадкой «ЯЗанят»</a><br>
                        - <a href="/files/agreement.pdf" target="_blank">с пользовательским соглашением
                        электронной площадки «ЯЗанят»</a><br>
                        - <a href="/files/personal_data.pdf" target="_blank">с политикой обработки персональных
                        данных электронной площадкой «ЯЗанят»</a>
                    </label>
                </b-form-group>

                <hr>

                <b-button :disabled="isLoading" @click="onRegisterClick" variant="primary">
                      <span v-show="isLoading" class="wait">
                          <b class="fad fa-spinner fa-pulse"></b>
                          Пожалуйста, подождите
                      </span>

                      <span v-show="!isLoading"> Продолжить</span>
                </b-button>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import {contractorApiClient} from "../../common/contractor-api-client";

export default {
    name: "register-page",
    data() {
        return {
            userInn: '',
            userName: '',
            userMiddleName: '',
            userLastName: '',
            userEmail: '',
            isLoading: false,
            isRegError: false,
            regErrors: [],
            oldEnoughCheckbox: false,
            readedTOS: false,
        }
    },

    methods: {
        validateForm() {
            let errors = []

            if (! this.readedTOS)
                errors.push('Подтвердите, что вы согласны с правилами сервиса')

            if (! this.oldEnoughCheckbox)
                errors.push('Подтвердите, что вам исполнилось 18 лет')

            return errors
        },

        onRegisterClick() {
            let errors = this.validateForm()

            if (errors.length > 0) {
                this.regErrors = errors
                this.isRegError = true
                return
            }

            this.isLoading = true
            this.isRegError = false

            contractorApiClient.register({
                firstname: this.userName,
                inn: this.userInn,
                lastname: this.userLastName,
                patronymic: this.userMiddleName,
                email: this.userEmail,
            })
                .then(r => {
                    window.location = '/my'
                })
                .catch(err => {
                    let response = err.response.data
                    let responseErrors = []

                    for (const [key, errors] of Object.entries(response.errors)) {
                        responseErrors.push(...errors)
                    }

                    this.regErrors = responseErrors
                    this.isRegError = true
                })
                .finally(() => {
                    this.isLoading = false
                })
        }
    }
}
</script>

<style scoped>
.errors-header {
    color: #bd0000;
    font-size: 17px;
}
</style>
