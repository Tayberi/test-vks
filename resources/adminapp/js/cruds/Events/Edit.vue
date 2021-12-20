<template>
  <div class="container-fluid">
    <form @submit.prevent="submitForm">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-primary card-header-icon">
              <div class="card-icon">
                <i class="material-icons">edit</i>
              </div>
              <h4 class="card-title">
                {{ $t('global.edit') }}
                <strong>{{ $t('cruds.event.title_singular') }}</strong>
              </h4>
            </div>
            <div class="card-body">
              <back-button></back-button>
            </div>
            <div class="card-body">
              <bootstrap-alert />
              <div class="row">
                <div class="col-md-12">
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.name,
                      'is-focused': activeField == 'name'
                    }"
                  >
                    <label class="bmd-label-floating required">{{
                      $t('cruds.event.fields.name')
                    }}</label>
                    <input
                      class="form-control"
                      type="text"
                      :value="entry.name"
                      @input="updateName"
                      @focus="focusField('name')"
                      @blur="clearFocus"
                      required
                    />
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.date_start,
                      'is-focused': activeField == 'date_start'
                    }"
                  >
                    <label class="bmd-label-floating required">{{
                      $t('cruds.event.fields.date_start')
                    }}</label>
                    <datetime-picker
                      class="form-control"
                      type="text"
                      :value="entry.date_start"
                      @input="updateDateStart"
                      @focus="focusField('date_start')"
                      @blur="clearFocus"
                      required
                    >
                    </datetime-picker>
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.date_end,
                      'is-focused': activeField == 'date_end'
                    }"
                  >
                    <label class="bmd-label-floating required">{{
                      $t('cruds.event.fields.date_end')
                    }}</label>
                    <datetime-picker
                      class="form-control"
                      type="text"
                      :value="entry.date_end"
                      @input="updateDateEnd"
                      @focus="focusField('date_end')"
                      @blur="clearFocus"
                      required
                    >
                    </datetime-picker>
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.studio.length !== 0,
                      'is-focused': activeField == 'studio'
                    }"
                  >
                    <label class="bmd-label-floating">{{
                      $t('cruds.event.fields.studio')
                    }}</label>
                    <v-select
                      name="studio"
                      label="name"
                      :key="'studio-field'"
                      :value="entry.studio"
                      :options="lists.studio"
                      :closeOnSelect="false"
                      multiple
                      @input="updateStudio"
                      @search.focus="focusField('studio')"
                      @search.blur="clearFocus"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <vue-button-spinner
                class="btn-primary"
                :status="status"
                :isLoading="loading"
                :disabled="loading"
              >
                {{ $t('global.save') }}
              </vue-button-spinner>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  data() {
    return {
      status: '',
      activeField: ''
    }
  },
  computed: {
    ...mapGetters('EventsSingle', ['entry', 'loading', 'lists'])
  },
  beforeDestroy() {
    this.resetState()
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler() {
        this.resetState()
        this.fetchEditData(this.$route.params.id)
      }
    }
  },
  methods: {
    ...mapActions('EventsSingle', [
      'fetchEditData',
      'updateData',
      'resetState',
      'setName',
      'setDateStart',
      'setDateEnd',
      'setStudio'
    ]),
    updateName(e) {
      this.setName(e.target.value)
    },
    updateDateStart(e) {
      this.setDateStart(e.target.value)
    },
    updateDateEnd(e) {
      this.setDateEnd(e.target.value)
    },
    updateStudio(value) {
      this.setStudio(value)
    },
    submitForm() {
      this.updateData()
        .then(() => {
          this.$router.push({ name: 'events.index' })
          this.$eventHub.$emit('update-success')
        })
        .catch(error => {
          this.status = 'failed'
          _.delay(() => {
            this.status = ''
          }, 3000)
        })
    },
    focusField(name) {
      this.activeField = name
    },
    clearFocus() {
      this.activeField = ''
    }
  }
}
</script>
