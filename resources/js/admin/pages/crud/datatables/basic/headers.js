!function(e){var t={};function n(a){if(t[a])return t[a].exports;var r=t[a]={i:a,l:!1,exports:{}};return e[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,a){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(a,r,function(t){return e[t]}.bind(null,r));return a},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=651)}({651:function(e,t,n){"use strict";var a={init:function(){$("#kt_table_1").DataTable({responsive:!0,columnDefs:[{targets:-1,title:"Actions",orderable:!1,render:function(e,t,n,a){return'\n                        <span class="dropdown">\n                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">\n                              <i class="la la-ellipsis-h"></i>\n                            </a>\n                            <div class="dropdown-menu dropdown-menu-right">\n                                <a class="dropdown-item" href="#"><i class="la la-edit"></i> Edit Details</a>\n                                <a class="dropdown-item" href="#"><i class="la la-leaf"></i> Update Status</a>\n                                <a class="dropdown-item" href="#"><i class="la la-print"></i> Generate Report</a>\n                            </div>\n                        </span>\n                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">\n                          <i class="la la-edit"></i>\n                        </a>'}},{targets:8,render:function(e,t,n,a){var r={1:{title:"Pending",class:"kt-badge--brand"},2:{title:"Delivered",class:" kt-badge--danger"},3:{title:"Canceled",class:" kt-badge--primary"},4:{title:"Success",class:" kt-badge--success"},5:{title:"Info",class:" kt-badge--info"},6:{title:"Danger",class:" kt-badge--danger"},7:{title:"Warning",class:" kt-badge--warning"}};return void 0===r[e]?e:'<span class="kt-badge '+r[e].class+' kt-badge--inline kt-badge--pill">'+r[e].title+"</span>"}},{targets:9,render:function(e,t,n,a){var r={1:{title:"Online",state:"danger"},2:{title:"Retail",state:"primary"},3:{title:"Direct",state:"success"}};return void 0===r[e]?e:'<span class="kt-badge kt-badge--'+r[e].state+' kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-'+r[e].state+'">'+r[e].title+"</span>"}}]})}};jQuery(document).ready((function(){a.init()}))}});