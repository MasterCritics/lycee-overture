<template>
    <el-menu router mode="horizontal" :default-active="$route.path">
        <el-menu-item index="/">
            <router-link to="/">{{ $t('nav.welcome') }}</router-link>
        </el-menu-item>
        <el-menu-item index="/rules">
            <router-link to="/rules">{{ $t('nav.rules') }}</router-link>
        </el-menu-item>
        <el-menu-item index="/cards">
            <router-link to="/cards">{{ $t('nav.cardList') }}</router-link>
        </el-menu-item>

        <li class="language-links">
            <a :href="enHref" class="language-link" :class="{ active: locale !== 'ja' }">
                <img src="../../images/flags/gb.png" alt="English" title="English" />
            </a>
            <a :href="jaHref" class="language-link" :class="{ active: locale === 'ja' }">
                <img src="../../images/flags/jp.png" alt="日本語" title="日本語" />
            </a>
        </li>
    </el-menu>
</template>

<script>
function getLocaleChangeUrl(vueComponent, locale) {
  let url = vueComponent.$route.fullPath;
  url += url.indexOf('?') >= 0 ? '&' : '?';
  url += `locale=${locale}`;
  return url;
}

  /** @class NavMenu */
  export default {
    components: {},
    data() {
      return {
        locale: window.locale,
      };
    },
    computed: {
      enHref() {
        return getLocaleChangeUrl(this, 'en');
      },
      jaHref() {
        return getLocaleChangeUrl(this, 'ja');
      },
    },
  };
</script>

<style scoped lang="scss">
.language-links {
    float: right;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.language-link {
    margin-left: 0.5em;

    &:not(.active) {
        opacity: 0.25;
    }
}
</style>
