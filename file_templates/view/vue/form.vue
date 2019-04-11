<template>
  <form @submit.prevent="submit_form">
    <HintTop v-show="form_message !== ''" :text="form_message" />
    
    <fieldset>
      [[content]]
    </fieldset>
    
    <!-- 提交按钮 -->
    <ButtonSubmit :disabled="false" @submit-form="submit_form" />
  
  </form>

</template>

<script>
export default {
  name: 'BizForm',

  props: {
    value: {
      type: Object,
      required: false,
      default: null
    }
  },
  
  data() {
    return {
      class_name: 'biz',
      form_message: '',

      item: this.value
        ? { ...this.value }
        : {
          name: '',
          brief_name: '',
          url_logo: '',
          industry_ids: ''
        }
    }
  },
  
  methods: {
    // 提交表单
    submit_form() {
      // 重置错误提示
      this.form_message = ''

      console.log('item: ', this.item)
      this.$emit('submit', this.item) // 触发父组件submit事件
    }

  }
}
</script>

<style scoped>

</style>
