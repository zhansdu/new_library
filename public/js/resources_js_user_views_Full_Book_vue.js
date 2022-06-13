"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_user_views_Full_Book_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mixins_goTo__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../mixins/goTo */ "./resources/js/user/mixins/goTo.js");
/* harmony import */ var _mixins_search__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../mixins/search */ "./resources/js/user/mixins/search.js");
/* harmony import */ var _common_assets_icons_RightLittle__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../common/assets/icons/RightLittle */ "./resources/js/common/assets/icons/RightLittle.vue");
/* harmony import */ var _common_assets_icons_X__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../common/assets/icons/X */ "./resources/js/common/assets/icons/X.vue");
/* harmony import */ var _common_assets_icons_Save__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../common/assets/icons/Save */ "./resources/js/common/assets/icons/Save.vue");
/* harmony import */ var _common_assets_icons_Print__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../common/assets/icons/Print */ "./resources/js/common/assets/icons/Print.vue");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }

function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }

function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }

function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    modal: Boolean,
    id: [String, Number]
  },
  mixins: [_mixins_goTo__WEBPACK_IMPORTED_MODULE_0__.goTo, _mixins_search__WEBPACK_IMPORTED_MODULE_1__.getBookImage],
  components: {
    RightLittle: _common_assets_icons_RightLittle__WEBPACK_IMPORTED_MODULE_2__["default"],
    X: _common_assets_icons_X__WEBPACK_IMPORTED_MODULE_3__["default"],
    Save: _common_assets_icons_Save__WEBPACK_IMPORTED_MODULE_4__["default"],
    Print: _common_assets_icons_Print__WEBPACK_IMPORTED_MODULE_5__["default"]
  },
  data: function data() {
    return {
      data: {
        image: '',
        isbn: '',
        description: '',
        content: ''
      },
      array_data: [],
      xml: [],
      link: '',
      contentExpanded: false,
      printing: false
    };
  },
  methods: {
    expandContent: function expandContent(data, bool) {
      if (this.xml && data.content) {
        if (bool) {
          data.content = this.getFromCatalog(this.xml, '505.a').split('--').join('<br>');
          this.contentExpanded = true;
        } else {
          data.content = data.content.split(/--|<br>/).splice(0, 2).join('--');
          this.contentExpanded = false;
        }
      }
    },
    closeModal: function closeModal() {
      this.$emit('close');
      document.documentElement.classList.remove('overflow-hidden');
    },
    copyLink: function copyLink() {
      var copyText = document.createElement('input');
      copyText.value = this.link;
      document.body.appendChild(copyText);
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      /* For mobile devices */

      try {
        document.execCommand("copy");
        alert('Copied successfully ' + copyText.value);
      } catch (e) {
        alert('Something went wrong');
      }

      document.body.removeChild(copyText);
    },
    printPage: function printPage() {
      if (!this.printing) {
        var routeData = this.$router.resolve({
          name: 'full_book',
          query: {
            id: this.data.id,
            mode: 'print',
            contentExpanded: this.contentExpanded
          }
        });
        window.open(routeData.href, '_blank');
        return 0;
      }

      window.print();
    },
    capitalize: function capitalize(s) {
      var string = s.slice();
      if (typeof string !== 'string') return '';
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    objectWithoutKey: function objectWithoutKey(object, key) {
      var deletedKey = object[key],
          otherKeys = _objectWithoutProperties(object, [key].map(_toPropertyKey));

      return otherKeys;
    },
    loadData: function loadData() {
      var _this = this;

      this.$store.commit('setFullPageLoading', true);
      this.$http.get('/media/show/' + this.id).then(function (response) {
        _this.xml = response.data.xmlInfo;
        _this.data = Object.assign(_this.data, _this.importFromXML(response));
        _this.data.link = _this.link;
        _this.array_data = _this.convertToArray(objectWithoutKey(_this.data, ['id', 'type_key', 'issn', 'status', 'available', 'total', 'image', 'description', 'content', 'subject_terms']));
        console.log(_this.array_data);

        try {
          _this.getBookImage(_this.data, !_this.data.description);
        } catch (e) {}
      })["catch"](function (error) {
        console.error(error);
      }).then(function () {
        _this.$store.commit('setFullPageLoading', false);
      }).then(function () {
        if (_this.printing) {
          _this.expandContent(_this.data, _this.$route.query.contentExpanded == 'true');
        }
      }).then(function () {
        if (_this.printing) {
          _this.printPage();

          window.close();
        }
      });
    },
    importFromXML: function importFromXML(response) {
      // need to have image in data
      var data = response.data.res;
      var xml = response.data.xmlInfo;

      if (xml) {
        data.description = this.getFromCatalog(xml, '520.a');
        var moreDescription = this.getFromCatalog(xml, '520.b');

        if (moreDescription) {
          data.description += '<br>' + moreDescription;
        }

        data.content = this.getFromCatalog(xml, '505.a');
        this.expandContent(data, false);
        data.attribution = this.getFromCatalog(xml, '245.c');
        data.subject_terms = this.getFromCatalog(xml, '650.x');
      }

      return data;
    },
    getFromCatalog: function getFromCatalog(xml, code) {
      var data = xml.find(function (elem) {
        return elem.id == code;
      });
      return data ? data.data : null;
    },
    convertToArray: function convertToArray(object) {
      var arr = [];

      for (var key in object) {
        var obj = {
          key: key,
          value: object[key]
        };
        arr.push(obj);
      }

      return arr;
    }
  },
  created: function created() {
    if (!this.id) {
      this.id = this.$route.query.id;

      if (!this.id) {
        this.goTo('home');
      }
    }

    this.link = window.location.protocol + '//' + window.location.hostname + '/book_full?id=' + this.id;

    if (!this.printing) {
      this.printing = this.$route.query.mode;
    }

    this.loadData();
  }
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.image[data-v-7946ad42]{\n\tmin-width:12em;\n\tmax-width:12em;\n\tmin-height:15em;\n\tmax-height:15em;\n\tbackground-repeat: no-repeat;\n\tbackground-size: 100% 100%;\n}\n.bg-greyer[data-v-7946ad42]{\n\tbackground: rgba(0,0,0, 0.3 ) !important;\n}\n.title[data-v-7946ad42]{\n\tdisplay:flex;\n\talign-items:flex-end;\n}\n.title>.text[data-v-7946ad42]{\n\tpadding-right:.3125em;\n\tfont-size:1.5em;\n}\n.title>.tline[data-v-7946ad42]{\n\theight:1px;\n\tflex:1;\n\tbackground:#DADADA;\n\tmargin-bottom:.5em;\n}\ntd[data-v-7946ad42]{\n\tborder-top: none;\n\tpadding-left: 0;\n}\n.mh-100[data-v-7946ad42]{\n\tmin-height: 100%;\n}\n.content[data-v-7946ad42]{\n\tmax-width:1120px;\n\twidth:100%;\n\tmin-height: 100vh;\n\theight:100%;\n}\n.close_icon[data-v-7946ad42]{\n\tcolor:white;\n\tposition: absolute;\n\ttop:8%;\n\tright:4%;\n\tfont-size:2em;\n}\n.overflow-y-auto[data-v-7946ad42]{\n\toverflow-y: auto;\n}\n@media screen and (max-width: 1300px) {\n.close_icon[data-v-7946ad42]{\n\t\ttop:1%;\n\t\tcolor:black;\n\t\tfont-size:1.5em;\n}\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_style_index_0_id_7946ad42_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_style_index_0_id_7946ad42_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_style_index_0_id_7946ad42_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/common/assets/icons/Print.vue":
/*!****************************************************!*\
  !*** ./resources/js/common/assets/icons/Print.vue ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Print_vue_vue_type_template_id_09e09e3c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Print.vue?vue&type=template&id=09e09e3c& */ "./resources/js/common/assets/icons/Print.vue?vue&type=template&id=09e09e3c&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");

var script = {}


/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  script,
  _Print_vue_vue_type_template_id_09e09e3c___WEBPACK_IMPORTED_MODULE_0__.render,
  _Print_vue_vue_type_template_id_09e09e3c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/common/assets/icons/Print.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/common/assets/icons/Save.vue":
/*!***************************************************!*\
  !*** ./resources/js/common/assets/icons/Save.vue ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Save_vue_vue_type_template_id_1414cbd8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Save.vue?vue&type=template&id=1414cbd8& */ "./resources/js/common/assets/icons/Save.vue?vue&type=template&id=1414cbd8&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");

var script = {}


/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  script,
  _Save_vue_vue_type_template_id_1414cbd8___WEBPACK_IMPORTED_MODULE_0__.render,
  _Save_vue_vue_type_template_id_1414cbd8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/common/assets/icons/Save.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/user/views/Full/Book.vue":
/*!***********************************************!*\
  !*** ./resources/js/user/views/Full/Book.vue ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Book_vue_vue_type_template_id_7946ad42_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Book.vue?vue&type=template&id=7946ad42&scoped=true& */ "./resources/js/user/views/Full/Book.vue?vue&type=template&id=7946ad42&scoped=true&");
/* harmony import */ var _Book_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Book.vue?vue&type=script&lang=js& */ "./resources/js/user/views/Full/Book.vue?vue&type=script&lang=js&");
/* harmony import */ var _Book_vue_vue_type_style_index_0_id_7946ad42_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css& */ "./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Book_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Book_vue_vue_type_template_id_7946ad42_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _Book_vue_vue_type_template_id_7946ad42_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "7946ad42",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/user/views/Full/Book.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/user/views/Full/Book.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/user/views/Full/Book.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Book.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css& ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_style_index_0_id_7946ad42_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=style&index=0&id=7946ad42&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/common/assets/icons/Print.vue?vue&type=template&id=09e09e3c&":
/*!***********************************************************************************!*\
  !*** ./resources/js/common/assets/icons/Print.vue?vue&type=template&id=09e09e3c& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Print_vue_vue_type_template_id_09e09e3c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Print_vue_vue_type_template_id_09e09e3c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Print_vue_vue_type_template_id_09e09e3c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Print.vue?vue&type=template&id=09e09e3c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/common/assets/icons/Print.vue?vue&type=template&id=09e09e3c&");


/***/ }),

/***/ "./resources/js/common/assets/icons/Save.vue?vue&type=template&id=1414cbd8&":
/*!**********************************************************************************!*\
  !*** ./resources/js/common/assets/icons/Save.vue?vue&type=template&id=1414cbd8& ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Save_vue_vue_type_template_id_1414cbd8___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Save_vue_vue_type_template_id_1414cbd8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Save_vue_vue_type_template_id_1414cbd8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Save.vue?vue&type=template&id=1414cbd8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/common/assets/icons/Save.vue?vue&type=template&id=1414cbd8&");


/***/ }),

/***/ "./resources/js/user/views/Full/Book.vue?vue&type=template&id=7946ad42&scoped=true&":
/*!******************************************************************************************!*\
  !*** ./resources/js/user/views/Full/Book.vue?vue&type=template&id=7946ad42&scoped=true& ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_template_id_7946ad42_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_template_id_7946ad42_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Book_vue_vue_type_template_id_7946ad42_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Book.vue?vue&type=template&id=7946ad42&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=template&id=7946ad42&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/common/assets/icons/Print.vue?vue&type=template&id=09e09e3c&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/common/assets/icons/Print.vue?vue&type=template&id=09e09e3c& ***!
  \**************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "svg",
    {
      staticClass: "svg-inline--fa fa-w-16",
      attrs: { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 45 45" }
    },
    [
      _c("g", [
        _c("path", {
          attrs: {
            fill: "currentColor",
            d:
              "M42.5,19.408H40V1.843c0-0.69-0.561-1.25-1.25-1.25H6.25C5.56,0.593,5,1.153,5,1.843v17.563H2.5c-1.381,0-2.5,1.119-2.5,2.5v20c0,1.381,1.119,2.5,2.5,2.5h40c1.381,0,2.5-1.119,2.5-2.5v-20C45,20.525,43.881,19.408,42.5,19.408zM32.531,38.094H12.468v-5h20.063V38.094z M37.5,19.408H35c-1.381,0-2.5,1.119-2.5,2.5v5h-20v-5c0-1.381-1.119-2.5-2.5-2.5H7.5V3.093h30V19.408z M32.5,8.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,8.792,32.5,8.792zM32.5,13.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,13.792,32.5,13.792zM32.5,18.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,18.792,32.5,18.792z"
          }
        })
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/common/assets/icons/Save.vue?vue&type=template&id=1414cbd8&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/common/assets/icons/Save.vue?vue&type=template&id=1414cbd8& ***!
  \*************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "svg",
    {
      staticClass: "svg-inline--fa fa-w-16",
      attrs: { viewBox: "0 0 24 24", xmlns: "http://www.w3.org/2000/svg" }
    },
    [
      _c("path", {
        attrs: {
          d:
            "M17.25 0C17.157 0 8.25 0 8.25 0C6.66298 0 5.25001 1.45576 5.25001 3.00002L4.38301 3.02026C2.79675 3.02026 1.5 4.45574 1.5 6V21C1.5 22.5443 2.91301 24 4.50002 24H15.75C17.337 24 18.75 22.5443 18.75 21H19.5C21.087 21 22.5 19.5443 22.5 18V6.01801L17.25 0ZM15.75 22.5H4.50002C3.71252 22.5 3.00003 21.7643 3.00003 21V6C3.00003 5.23576 3.64128 4.52475 4.42878 4.52475L5.25001 4.50001V18C5.25001 19.5443 6.66298 21 8.25 21H17.25C17.25 21.7643 16.5375 22.5 15.75 22.5ZM21 18C21 18.7642 20.2875 19.5 19.5 19.5H8.25C7.46249 19.5 6.75 18.7643 6.75 18V3.00002C6.75 2.23578 7.46249 1.50003 8.25 1.50003H15.75C15.738 3.22728 15.75 4.51878 15.75 4.51878C15.75 6.07727 17.1525 7.50003 18.75 7.50003C18.75 7.50003 19.5465 7.50003 21 7.50003V18ZM18.75 6C17.9513 6 17.25 4.54877 17.25 3.77026C17.25 3.77026 17.25 2.98125 17.25 1.52326V1.52175L21 6H18.75ZM17.25 10.5135H10.5C10.086 10.5135 9.75002 10.8488 9.75002 11.2628C9.75002 11.6768 10.086 12.012 10.5 12.012H17.25C17.664 12.012 18 11.6767 18 11.2628C18 10.8488 17.664 10.5135 17.25 10.5135ZM17.25 14.2598H10.5C10.086 14.2598 9.75002 14.595 9.75002 15.009C9.75002 15.423 10.086 15.7582 10.5 15.7582H17.25C17.664 15.7582 18 15.423 18 15.009C18 14.595 17.664 14.2598 17.25 14.2598Z",
          fill: "currentColor"
        }
      })
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=template&id=7946ad42&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/user/views/Full/Book.vue?vue&type=template&id=7946ad42&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      class: _vm.modal
        ? "d-flex justify-content-center bg-greyer padding mh-100 overflow-y-auto"
        : "padding"
    },
    [
      _c(
        "div",
        {
          staticClass: "d-flex align-items-start bg-white border-top py-4",
          class: { "content px-5": _vm.modal }
        },
        [
          _vm.printing != "print"
            ? _c("div", { staticClass: "d-none d-md-block mr-5" }, [
                _c("div", {
                  staticClass: "image rounded bg-grey",
                  style: "background-image: url(" + this.data.image + ")"
                }),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass:
                      "d-flex align-items-center cursor-pointer py-2 mt-2",
                    on: {
                      click: function($event) {
                        return _vm.copyLink()
                      }
                    }
                  },
                  [
                    _c("Save"),
                    _vm._v(" "),
                    _c("span", { staticClass: "ml-2" }, [
                      _vm._v(_vm._s(_vm.$t("copy_link")))
                    ])
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass:
                      "d-flex align-items-center cursor-pointer py-2",
                    on: {
                      click: function($event) {
                        return _vm.printPage()
                      }
                    }
                  },
                  [
                    _c("Print"),
                    _vm._v(" "),
                    _c("span", { staticClass: "ml-2" }, [
                      _vm._v(_vm._s(_vm.$t("print_page")))
                    ])
                  ],
                  1
                )
              ])
            : _vm._e(),
          _vm._v(" "),
          _c("div", { staticClass: "flex-fill" }, [
            _c("div", { staticClass: "d-flex flex-fill" }, [
              _c("div", { staticClass: "flex-fill" }, [
                _c(
                  "div",
                  {
                    staticClass:
                      "overflow-hidden title font-weight-bold font-size-24 cursor-pointer"
                  },
                  [_vm._v(_vm._s(_vm.data.title))]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "text-grey mt-2" }, [
                  _vm.data.author
                    ? _c("div", [
                        _vm._v(
                          _vm._s(_vm.data.author) + ", " + _vm._s(_vm.data.year)
                        )
                      ])
                    : _vm._e()
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "d-flex text-center text-blue mt-3" },
                  [
                    _vm.data.type
                      ? _c(
                          "div",
                          { staticClass: "rounded-lg bg-lightblue p-1 px-3" },
                          [_vm._v(_vm._s(_vm.$t(_vm.data.type)))]
                        )
                      : _vm._e(),
                    _vm._v(" "),
                    _vm.data.call_number
                      ? _c(
                          "div",
                          {
                            staticClass: "rounded-lg bg-lightblue p-1 px-3 ml-3"
                          },
                          [_vm._v(_vm._s(_vm.data.call_number))]
                        )
                      : _vm._e()
                  ]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "d-block d-sm-none mt-3 text-center" },
                  [
                    _c(
                      "div",
                      {
                        staticClass: "bg-lightgrey rounded-lg p-2 text-no-wrap"
                      },
                      [
                        _vm._v(
                          "\n\t\t\t\t\t\t\t" +
                            _vm._s(
                              _vm.data.available + " / " + _vm.data.total
                            ) +
                            "\n\t\t\t\t\t\t\t" +
                            _vm._s(_vm.$t("availability")) +
                            "\n\t\t\t\t\t\t"
                        )
                      ]
                    )
                  ]
                ),
                _vm._v(" "),
                _vm.data.description
                  ? _c("div", { staticClass: "mt-3" }, [
                      _c("div", { staticClass: "text-grey font-size-14" }, [
                        _vm._v(
                          "\n\t\t\t\t\t\t\t" +
                            _vm._s(_vm.$t("description")) +
                            "\n\t\t\t\t\t\t"
                        )
                      ]),
                      _vm._v(" "),
                      _c("div", {
                        staticClass: "mt-1",
                        domProps: { innerHTML: _vm._s(_vm.data.description) }
                      })
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.data.content
                  ? _c("div", { staticClass: "mt-3" }, [
                      _c("div", { staticClass: "text-grey font-size-14" }, [
                        _vm._v(
                          "\n\t\t\t\t\t\t\t" +
                            _vm._s(_vm.$t("content")) +
                            "\n\t\t\t\t\t\t"
                        )
                      ]),
                      _vm._v(" "),
                      _c("div", {
                        staticClass: "mt-1",
                        domProps: { innerHTML: _vm._s(_vm.data.content) }
                      }),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass:
                            "text-blue cursor-pointer font-weight-bold mt-2"
                        },
                        [
                          !_vm.contentExpanded
                            ? _c(
                                "div",
                                {
                                  on: {
                                    click: function($event) {
                                      return _vm.expandContent(_vm.data, true)
                                    }
                                  }
                                },
                                [_vm._v(_vm._s(_vm.$t("expand")))]
                              )
                            : _c(
                                "div",
                                {
                                  on: {
                                    click: function($event) {
                                      return _vm.expandContent(_vm.data, false)
                                    }
                                  }
                                },
                                [_vm._v(_vm._s(_vm.$t("shrink")))]
                              )
                        ]
                      )
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.data.subject_terms
                  ? _c("div", { staticClass: "mt-3" }, [
                      _c("div", { staticClass: "text-grey font-size-14" }, [
                        _vm._v(
                          "\n\t\t\t\t\t\t\t" +
                            _vm._s(_vm.$t("subject_terms")) +
                            "\n\t\t\t\t\t\t"
                        )
                      ]),
                      _vm._v(" "),
                      _c("div", {
                        staticClass: "mt-1",
                        domProps: { innerHTML: _vm._s(_vm.data.subject_terms) }
                      })
                    ])
                  : _vm._e()
              ]),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "d-none d-sm-block text-center col-2 px-0" },
                [
                  _c(
                    "div",
                    { staticClass: "bg-lightgrey rounded-lg p-2 text-no-wrap" },
                    [
                      _vm._v(
                        "\n\t\t\t\t\t\t" +
                          _vm._s(_vm.data.available + " / " + _vm.data.total) +
                          "\n\t\t\t\t\t\t" +
                          _vm._s(_vm.$t("availability")) +
                          "\n\t\t\t\t\t"
                      )
                    ]
                  )
                ]
              )
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "title mt-4" }, [
              _c("div", { staticClass: "text" }, [
                _vm._v(
                  "\n\t\t\t\t\t" + _vm._s(_vm.$t("details")) + "\n\t\t\t\t"
                )
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "tline" })
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "d-none d-sm-block" }, [
              _c(
                "div",
                { staticClass: "mt-2" },
                _vm._l(
                  _vm.array_data.filter(function(info) {
                    return info.value
                  }),
                  function(info, index) {
                    return _c("div", { key: index, staticClass: "mt-2" }, [
                      _c("div", { staticClass: "text-grey" }, [
                        _vm._v(
                          "\n\t\t\t\t\t\t\t" +
                            _vm._s(_vm.$t(info.key)) +
                            ":\n\t\t\t\t\t\t"
                        )
                      ]),
                      _vm._v(" "),
                      _c("div", [
                        _vm._v(
                          "\n\t\t\t\t\t\t\t" +
                            _vm._s(info.value) +
                            "\n\t\t\t\t\t\t"
                        )
                      ])
                    ])
                  }
                ),
                0
              )
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "d-block d-sm-none" },
              _vm._l(_vm.array_data, function(info, index) {
                return _c("div", { key: index }, [
                  _c("div", { staticClass: "text-grey mt-3" }, [
                    _vm._v(
                      "\n\t\t\t\t\t\t" +
                        _vm._s(_vm.$t(_vm.array_data[index].key)) +
                        ":\n\t\t\t\t\t"
                    )
                  ]),
                  _vm._v(" "),
                  _c("div", [
                    _vm._v(
                      "\n\t\t\t\t\t\t" +
                        _vm._s(
                          _vm.array_data[index].value != undefined
                            ? _vm.array_data[index].value
                            : _vm.$t("undefined")
                        ) +
                        "\n\t\t\t\t\t"
                    )
                  ])
                ])
              }),
              0
            )
          ])
        ]
      ),
      _vm._v(" "),
      _vm.modal
        ? _c(
            "div",
            {
              staticClass: "close_icon cursor-pointer",
              on: {
                click: function($event) {
                  return _vm.closeModal()
                }
              }
            },
            [_c("X")],
            1
          )
        : _vm._e()
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);