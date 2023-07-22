<template>
  <svg
    version="1.1"
    id="headerSvg"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    x="0px"
    y="0px"
    viewBox="0 0 1920 312"
    style="enable-background: new 0 0 1920 312"
    xml:space="preserve">
    <g id="header-bg">
      <path
        id="header-bg-path"
        class="header-svg-bg"
        d="M0,276c0,24,12,36,36,36h388c40,0,60-20,60-60c0-16,8-24,24-24c48,0,72-24,72-72
			c0-16,8-24,24-24h1280c24,0,36-12,36-36V0H0V276z" />
    </g>
    <g class="header-avatar">
      <circle class="header-svg-bg" cx="484" cy="132" r="60" />
      <path
        class="header-avatar-border"
        d="M484,73c32.5,0,59,26.5,59,59s-26.5,59-59,59s-59-26.5-59-59S451.5,73,484,73 M484,72c-33.1,0-60,26.9-60,60s26.9,60,60,60 s60-26.9,60-60S517.1,72,484,72L484,72z" />
    </g>

    <g>
      <defs>
        <circle id="avatarClip" cx="484" cy="132" r="54" />
      </defs>
      <clipPath id="avatarPath">
        <use xlink:href="#avatarClip" style="overflow: visible" />
      </clipPath>
      <g style="clip-path: url(#avatarPath)">
        <image
          preserveAspectRatio="none meet"
          style="overflow: visible"
          width="216"
          height="216"
          :href="avatarUrl"
          transform="matrix(0.5 0 0 0.5 430 78)"></image>
      </g>
    </g>
  </svg>
</template>

<script setup lang="ts">
import avatarUrl from '@/assets/images/default-avatar.png';
</script>

<style lang="less">
.header-svg {
  width: 100%;
}
.header-svg-bg {
  transition: fill 0.2s ease-in-out;
  fill: var(--bg);
}
.header-avatar {
  filter: drop-shadow(-5px -5px 8px var(--wshadow0)) drop-shadow(5px 5px 8px var(--bshadow15));
}
.header-avatar-border {
  fill: var(--w-border);
}
</style>
