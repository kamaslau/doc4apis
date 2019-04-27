<template>
  <div id="content">
    <div id="class-title" class="row">
      <h2>文章</h2>
  
      <nav id="nav-class">
        <ul>
          <nuxt-link class="btn btn-outline-secondary" :to="'/' + class_name" exact><i class="fal fa-list"></i> 所有</nuxt-link>
          <nuxt-link class="btn btn-outline-secondary" :to="'/' + class_name + '/create'"><i class="fal fa-plus"></i> 添加</nuxt-link>
        </ul>
      </nav>
    </div>
    
    <nuxt-child></nuxt-child>
  </div>
</template>

<script>
export default {
  data() {
    return {
      class_name: 'article',
      id_name: 'article_id'
    }
  }

}
</script>

<style scoped>

</style>
