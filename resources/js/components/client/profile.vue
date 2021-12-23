<template>
  <div class="card">
    <div class="card-header">
      <h2 class="card-title"><b class="fad fa-user me-2"></b>Профиль</h2>
    </div>
    <b-tabs v-model="tabIndex" :no-fade="false" content-class="mt-3" fill>
      <b-tab>
        <template #title> <div class="px-2 py-2">Данные</div></template>
        <div class="card-body">
          <span class="text-danger"
            ><sup>*</sup> Для изменения ваших данных напишите в службу
            технической поддержки -
            <a href="mailto:info@izanyat.ru" target="_blank"
              >info@izanyat.ru</a
            ></span
          >
        </div>
        <div class="card-body">
          <div class="row mt-2">
            <div class="col-6">
              <div class="form-group">
                <label class="text-muted">Имя</label>
                <div class="font-weight-bold">{{ fullname }}</div>
              </div>
              <div class="form-group mt-3">
                <label class="text-muted">ИНН</label>
                <div class="font-weight-bold">{{ profile.inn }}</div>
              </div>

              <div class="form-group mt-3">
                <label class="text-muted">СНИЛС</label>
                <div class="font-weight-bold">{{ profile.snils }}</div>
              </div>

              <div class="form-group mt-3">
                <label class="text-muted">Паспорт</label>
                <div>
                  <span class="font-weight-bold">{{
                    profile.passport_series
                  }}</span
                  >&nbsp;<span class="font-weight-bold">{{
                    profile.passport_number
                  }}</span>
                </div>
              </div>

              <div class="form-group mt-3">
                <label class="text-muted">Кем выдан</label>
                <div class="font-weight-bold">
                  {{ profile.passport_issuer }}
                </div>
                <div>
                  (код подразделения:
                  <span class="font-weight-bold">{{
                    profile.passport_code
                  }}</span
                  >)
                </div>
              </div>
              <div class="form-group mt-3">
                <label class="text-muted">Дата выдачи</label>
                <div class="font-weight-bold">
                  {{ profile.passport_issue_date }}
                </div>
              </div>

              <div class="form-group mt-3">
                <label class="text-muted">Место рождения</label>
                <div class="font-weight-bold">{{ profile.birth_place }}</div>
              </div>
              <div class="form-group mt-3">
                <label class="text-muted">Дата рождения</label>
                <div class="font-weight-bold">{{ profile.birth_date }}</div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group mt-3">
                <label class="text-muted">Телефон</label>
                <div>
                  +<span class="font-weight-bold">{{ profile.phone }}</span>
                </div>
              </div>
              <div class="form-group mt-3">
                <label class="text-muted">Email</label>
                <div class="font-weight-bold">{{ profile.email }}</div>
              </div>
              <div class="form-group mt-3">
                <label class="text-muted">Адрес</label>
                <div>
                  Регион:
                  <span class="font-weight-bold">{{
                    profile.address_region
                  }}</span>
                </div>

                <div>
                  Город:
                  <span class="font-weight-bold">{{
                    profile.address_city
                  }}</span>
                </div>

                <div>
                  Улица:
                  <span class="font-weight-bold">{{
                    profile.address_street
                  }}</span>
                </div>

                <div>
                  Дом:
                  <span class="font-weight-bold">{{
                    profile.address_house
                  }}</span>
                </div>

                <div>
                  Корпус:
                  <span class="font-weight-bold">{{
                    profile.address_building
                  }}</span>
                </div>

                <div>
                  Квартира:
                  <span class="font-weight-bold">{{
                    profile.address_flat
                  }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </b-tab>
      <b-tab>
        <template #title> <div class="px-2 py-2">Смена пароля</div></template>
        <profile-change-password></profile-change-password>
      </b-tab>
      <b-tab>
        <template #title>
          <div class="px-2 py-2">Персональные данные</div>
        </template>
        <profile-personal :profile="profile"></profile-personal>
      </b-tab>
      <b-tab>
        <template #title>
          <div class="px-2 py-2">Электронная подпись</div>
        </template>

        <b-container class="px-5" style="min-height: 50vh">
          <personal-signme-init
            :setTab="setTab"
            v-if="signmeState == null"
          ></personal-signme-init>
          <signme-requested
            v-else-if="signmeState == 'request_in_progress'"
          ></signme-requested>
          <signme-requested
            v-else-if="signmeState == 'await_approve'"
          ></signme-requested>
          <signme-aproved
            v-else-if="signmeState == 'approved'"
          ></signme-aproved>
        </b-container>
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import HTTP from "../../common/http-client";
import ProfileChangePassword from "./profile-change-password";
import ProfilePersonal from "./profile-personal.vue";
import PersonalSignmeInit from "./personal-signme-init.vue";
import SignmeRequested from "../contractor/signme/signme-requested.vue";
import SignmeAproved from "../contractor/signme/signme-aproved.vue";
import HTTPV2 from "../../common/httpv2-client";

export default {
  components: {
    ProfileChangePassword,
    ProfilePersonal,
    PersonalSignmeInit,
    SignmeRequested,
    SignmeAproved,
  },
  data: () => ({
    profile: {},
    signmeState: null,
    tabIndex: 0,
  }),
  mounted() {
    this.$root.eventbus.on("requestSingCheck", (payload) =>
      this.signmeStateCheck()
    );
    const request = HTTP.get("me")
      .then((response) => {
        this.profile = response.data.me;
        this.$root.eventbus.emit("profilechange", {
          profile: this.profile,
        });
      })
      .catch((reject) => {
        console.log(reject);
      });
    setInterval(() => {
      this.signmeStateCheck();
    }, 10000);
  },
  methods: {
    signmeStateCheck() {
      HTTPV2.get("company/signme/state").then((res) => {
        this.signmeState = res.data.signme_state?.status || null;
      });
    },
    setTab(index) {
      this.tabIndex = index;
    },
  },
  computed: {
    fullname() {
      return `${this.profile.lastname} ${this.profile.firstname} ${this.profile.patronymic}`;
    },
  },
  beforeDestroy() {
    this.profile = {};
  },
};
</script>

<style>
</style>
