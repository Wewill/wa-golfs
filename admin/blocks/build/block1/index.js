(() => {
  "use strict";
  var e,
    t = {
      341: () => {
        const e = window.wp.blocks,
          t = window.React,
          l = window.wp.i18n,
          o = window.wp.blockEditor,
          a = window.wp.coreData,
          c = window.wp.element,
          n = JSON.parse('{"UU":"directory/wa-golfs-directory-block"}');
        (0, e.registerBlockType)(n.UU, {
          edit: function ({ context: { postType: e, postId: n } }) {
            const r = (0, o.useBlockProps)(),
              [i, s] = (0, a.useEntityProp)("postType", e, "meta", n);
            console.log("meta::", i);
            const { d_general_subtitle: d } = i,
              { d_general_introduction: m } = i,
              {
                d_identity_location: u,
                d_identity_area: p,
                d_identity_number_of_people: b,
                d_identity_livestock: f,
                d_identity_label: E,
                d_identity_commercialization: g,
              } = i,
              v = (E || []).map((e, l) =>
                (0, t.createElement)("li", { class: "lead", key: l }, e)
              ),
              _ = (g || []).map((e, l) =>
                (0, t.createElement)("p", { class: "mb-0", key: l }, e)
              ),
              w = [
                {
                  inputId: "d_general_subtitle",
                  metaKey: "d_general_subtitle",
                },
                {
                  inputId: "d_general_introduction",
                  metaKey: "d_general_introduction",
                },
              ];
            return (
              (0, c.useEffect)(() => {
                const e = (e) => (t) => {
                  const l = t.target.value;
                  s({ ...i, [e]: l });
                };
                return (
                  w.forEach(({ inputId: t, metaKey: l }) => {
                    const o = document.getElementById(t);
                    o && o.addEventListener("input", e(l));
                  }),
                  () => {
                    w.forEach(({ inputId: t, metaKey: l }) => {
                      const o = document.getElementById(t);
                      o && o.removeEventListener("input", e(l));
                    });
                  }
                );
              }, [s]),
              (0, t.createElement)(
                "div",
                { ...r },
                (0, t.createElement)(o.RichText, {
                  placeholder: (0, l.__)("Sous-titre", "wa-golfs"),
                  tagName: "h4",
                  allowedFormats: [],
                  disableLineBreaks: !0,
                  value: d,
                  onChange: (e) => {
                    const t = document.getElementById("d_general_subtitle");
                    t && (t.value = e);
                  },
                }),
                (0, t.createElement)(o.RichText, {
                  placeholder: (0, l.__)("Introduction", "wa-golfs"),
                  tagName: "p",
                  className: "lead",
                  value: m,
                  onChange: (e) => {
                    const t = document.getElementById("d_general_introduction");
                    t && (t.value = e);
                  },
                }),
                (0, t.createElement)(
                  "section",
                  { class: "mb-2 d-none" },
                  (0, t.createElement)(
                    "div",
                    { class: "container-fluid px-0" },
                    (0, t.createElement)(
                      "div",
                      { class: "row g-0" },
                      (0, t.createElement)(
                        "div",
                        {
                          class:
                            "col-lg-5 col-xl-5 bg-action-1 p-4 p-lg-5 rounded-bottom-4 rounded-bottom-right-0",
                          "data-aos": "fade-down",
                          "data-aos-delay": "300",
                        },
                        (0, t.createElement)(
                          "h6",
                          { class: "subline text-action-1" },
                          "Map"
                        )
                      ),
                      (0, t.createElement)(
                        "div",
                        {
                          class:
                            "col-lg overflow-hidden bg-color-layout p-4 p-lg-5 rounded-bottom-4 rounded-bottom-left-0",
                          "data-aos": "fade-left",
                          "data-aos-delay": "100",
                        },
                        (0, t.createElement)(
                          "div",
                          {
                            class:
                              "w-100 d-flex align-items-center justify-content-between",
                          },
                          (0, t.createElement)(
                            "h6",
                            { class: "subline text-action-1" },
                            "Carte d'identité"
                          )
                        ),
                        (0, t.createElement)(
                          "div",
                          {
                            class:
                              "row row-cols-1 row-cols-md-2 g-4 pt-3 py-2 py-md-5",
                          },
                          u &&
                            (0, t.createElement)(
                              "div",
                              { class: "col d-flex align-items-center" },
                              (0, t.createElement)("i", {
                                class: "bi bi-bootstrap flex-shrink-0 me-3 h4",
                              }),
                              (0, t.createElement)(
                                "div",
                                null,
                                (0, t.createElement)(
                                  "h6",
                                  { class: "fw-bold" },
                                  (0, l.__)("Location", "wa-golfs")
                                ),
                                (0, t.createElement)(
                                  "p",
                                  { class: "lead mb-0 mb-md-4" },
                                  u
                                )
                              )
                            ),
                          p &&
                            (0, t.createElement)(
                              "div",
                              { class: "col d-flex align-items-center" },
                              (0, t.createElement)("i", {
                                class: "bi bi-bootstrap flex-shrink-0 me-3 h4",
                              }),
                              (0, t.createElement)(
                                "div",
                                null,
                                (0, t.createElement)(
                                  "h6",
                                  { class: "fw-bold" },
                                  (0, l.__)("Area (in ha)", "wa-golfs")
                                ),
                                (0, t.createElement)(
                                  "p",
                                  { class: "lead mb-0 mb-md-4" },
                                  p
                                )
                              )
                            ),
                          b &&
                            (0, t.createElement)(
                              "div",
                              { class: "col d-flex align-items-center" },
                              (0, t.createElement)("i", {
                                class: "bi bi-bootstrap flex-shrink-0 me-3 h4",
                              }),
                              (0, t.createElement)(
                                "div",
                                null,
                                (0, t.createElement)(
                                  "h6",
                                  { class: "fw-bold" },
                                  (0, l.__)("Number of people", "wa-golfs")
                                ),
                                (0, t.createElement)(
                                  "p",
                                  { class: "lead mb-0 mb-md-4" },
                                  b
                                )
                              )
                            ),
                          f &&
                            (0, t.createElement)(
                              "div",
                              { class: "col d-flex align-items-center" },
                              (0, t.createElement)("i", {
                                class: "bi bi-bootstrap flex-shrink-0 me-3 h4",
                              }),
                              (0, t.createElement)(
                                "div",
                                null,
                                (0, t.createElement)(
                                  "h6",
                                  { class: "fw-bold" },
                                  (0, l.__)("Livestock", "wa-golfs")
                                ),
                                (0, t.createElement)(
                                  "p",
                                  { class: "lead mb-0 mb-md-4" },
                                  f
                                )
                              )
                            ),
                          E &&
                            (0, t.createElement)(
                              "div",
                              { class: "col d-flex align-items-center" },
                              (0, t.createElement)("i", {
                                class: "bi bi-bootstrap flex-shrink-0 me-3 h4",
                              }),
                              (0, t.createElement)(
                                "div",
                                null,
                                (0, t.createElement)(
                                  "h6",
                                  { class: "fw-bold" },
                                  (0, l.__)("Label", "wa-golfs")
                                ),
                                (0, t.createElement)(
                                  "ul",
                                  { class: "ps-4 list-group list-group-flush" },
                                  v
                                )
                              )
                            )
                        ),
                        (0, t.createElement)(
                          "div",
                          {
                            class:
                              "row row-cols-1 row-cols-md-1 g-4 py-2 py-md-5",
                          },
                          g &&
                            (0, t.createElement)(
                              "div",
                              { class: "col d-flex align-items-center" },
                              (0, t.createElement)("i", {
                                class: "bi bi-bootstrap flex-shrink-0 me-3 h4",
                              }),
                              (0, t.createElement)(
                                "div",
                                null,
                                (0, t.createElement)(
                                  "h6",
                                  { class: "fw-bold" },
                                  (0, l.__)("Commercialization", "wa-golfs")
                                ),
                                _
                              )
                            )
                        )
                      )
                    )
                  )
                ),
                (0, t.createElement)(
                  "section",
                  { class: "content" },
                  (0, t.createElement)(o.InnerBlocks, {
                    allowedBlocks: [
                      "core/image",
                      "core/heading",
                      "core/paragraph",
                      "core/list",
                      "core/quote",
                      "core/pullquote",
                      "core/block",
                      "core/button",
                      "core/buttons",
                      "core/column",
                      "core/columns",
                      "core/table",
                      "core/text-columns",
                      "coblocks/accordion",
                      "coblocks/accordion-item",
                      "coblocks/alert",
                      "coblocks/counter",
                      "coblocks/column",
                      "coblocks/row",
                      "coblocks/dynamic-separator",
                      "coblocks/logos",
                      "coblocks/icon",
                      "coblocks/buttons",
                    ],
                    template: [
                      [
                        "core/paragraph",
                        {
                          placeholder:
                            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
                        },
                      ],
                      ["core/image", {}],
                    ],
                    templateLock: !1,
                  })
                )
              )
            );
          },
          save: function () {
            return (0, t.createElement)(
              "div",
              { ...o.useBlockProps.save() },
              (0, t.createElement)(o.InnerBlocks.Content, null)
            );
          },
        });
      },
    },
    l = {};
  function o(e) {
    var a = l[e];
    if (void 0 !== a) return a.exports;
    var c = (l[e] = { exports: {} });
    return t[e](c, c.exports, o), c.exports;
  }
  (o.m = t),
    (e = []),
    (o.O = (t, l, a, c) => {
      if (!l) {
        var n = 1 / 0;
        for (d = 0; d < e.length; d++) {
          for (var [l, a, c] = e[d], r = !0, i = 0; i < l.length; i++)
            (!1 & c || n >= c) && Object.keys(o.O).every((e) => o.O[e](l[i]))
              ? l.splice(i--, 1)
              : ((r = !1), c < n && (n = c));
          if (r) {
            e.splice(d--, 1);
            var s = a();
            void 0 !== s && (t = s);
          }
        }
        return t;
      }
      c = c || 0;
      for (var d = e.length; d > 0 && e[d - 1][2] > c; d--) e[d] = e[d - 1];
      e[d] = [l, a, c];
    }),
    (o.o = (e, t) => Object.prototype.hasOwnProperty.call(e, t)),
    (() => {
      var e = { 986: 0, 846: 0 };
      o.O.j = (t) => 0 === e[t];
      var t = (t, l) => {
          var a,
            c,
            [n, r, i] = l,
            s = 0;
          if (n.some((t) => 0 !== e[t])) {
            for (a in r) o.o(r, a) && (o.m[a] = r[a]);
            if (i) var d = i(o);
          }
          for (t && t(l); s < n.length; s++)
            (c = n[s]), o.o(e, c) && e[c] && e[c][0](), (e[c] = 0);
          return o.O(d);
        },
        l = (globalThis.webpackChunkwa_golfs_directory_block =
          globalThis.webpackChunkwa_golfs_directory_block || []);
      l.forEach(t.bind(null, 0)), (l.push = t.bind(null, l.push.bind(l)));
    })();
  var a = o.O(void 0, [846], () => o(341));
  a = o.O(a);
})();
