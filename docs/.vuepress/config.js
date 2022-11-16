module.exports = {
  title: "Laravel Model States",
  description: "Better state management for Eloquent Models.",
  base: "/laravel-state/",
  theme: "vt",
  themeConfig: {
    enableDarkMode: true,
    repo: "moirei/laravel-state",
    repoLabel: "Github",
    docsRepo: "moirei/laravel-state",
    docsDir: "docs",
    docsBranch: "master",
    sidebar: [
      {
        title: "Installation",
        children: ["introduction", "installation"],
      },
      {
        title: "Defining States",
        path: "/guide/defining-states",
      },
      {
        title: "State Transitions",
        path: "/guide/transitions",
      },
      {
        title: "Multicast Attributes",
        path: "/guide/multicast",
      },
      {
        title: "Hooks",
        path: "/guide/hooks",
      },
    ],
    nav: [
      { text: "Guide", link: "/guide/defining-states" },
      {
        text: "Github",
        link: "https://github.com/moirei/laravel-state",
        target: "_self",
      },
      // { text: 'External', link: 'https://moirei.com', target:'_self' },
    ],
  },
  plugins: [
    "@vuepress/register-components",
    "@vuepress/active-header-links",
    "@vuepress/pwa",
    "seo",
  ],
};
