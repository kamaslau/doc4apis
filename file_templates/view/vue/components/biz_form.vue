<template>
  <form @submit.prevent="submit_form">
    <HintTop v-show="form_message !== ''" :text="form_message" />
    
    <fieldset>
      <div class="form-group row">
        <label class="col-form-label col-sm-2">简称</label>
        
        <div class="input-group col-sm-10">
          <input v-model.trim.lazy="item.brief_name" class="form-control" placeholder="简短的企业或品牌名称，例如礼聘、谷歌、华为等" required>
        </div>
      </div>
      
      <div class="form-group row">
        <label class="col-form-label col-sm-2">名称</label>
        
        <div class="input-group col-sm-10">
          <input v-model.trim.lazy="item.name" class="form-control" placeholder="需与营业执照中名称一致" required>
        </div>
      </div>
      
      <Uploader v-model="item.url_logo" label="LOGO" name_to_upload="url_logo" max_count="1" :class_name="class_name" />
      <pre>
      item.url_logo: {{ item.url_logo }}
      </pre>
    </fieldset>
    
    <!-- 提交按钮 -->
    <ButtonSubmit :disabled="false" @submit-form="submit_form" />
  
  </form>

</template>

<script>
export default {
  name: 'articleForm',

  props: {
    value: {
      type: Object,
      required: false,
      default: null
    }
  },
  
  data() {
    return {
      class_name: 'article',
      form_message: '',

      item: this.value
        ? { ...this.value }
        : {
          name: '',
          brief_name: '',
          url_logo: ''
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
