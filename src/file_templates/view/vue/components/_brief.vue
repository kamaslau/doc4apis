<template>
  
  <section class="article-brief">
    <div class="article-excerpt row">
      <figure class="logo article-logo">
        <img :alt="item.brief_name + 'LOGO'" :src="'https://images.bandaodian.com/' + item.url_logo">
      </figure>
  
      <div class="article-name">
        <h2 class="article-briefname">
          {{ item.brief_name }}
  
          <nuxt-link :to="'/' + class_name + '/edit?id=' + item[id_name]">
            <span class="item-id">ID{{ item[id_name] }}</span>
          </nuxt-link>
        </h2>
        
        <h3 class="article-name" v-if="item.brief_name">{{ item.name }}</h3>
      </div>
    </div>
    
    <div class="article-ratings">
      <ul class="row">
        <li>面试 4.6</li>
        <li>交通 3.2</li>
        <li>环境 4.3</li>
      </ul>
    </div>
  </section>
  
</template>

<script>
/**
 * 组件 企业简述
 */
export default {
  name: 'articleBrief',
  
  props: {
    item: {
      type: Object,
      default() {
        return {
        }
      }
    }
  },

  data() {
    return {
      class_name: 'article'
    }
  },

  computed: {
    id_name() {
      return this.class_name + '_id'
    }
  },
  
  created() {
    // console.clear()
    console.log('item: ', this.item)
  }
}
</script>

<style scoped>
  .article-brief {}
  
    .article-title {font-size:larger;}
    .item-id {color:#999}
</style>
