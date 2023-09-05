function editormd(id: string, options: EditorMdOptions): EditorMd {}

class EditorMd {
  constructor(options: EditorMdOptions);
  // 添加 Editor.md 方法的类型声明
  // 例如，如果您想声明一个自定义方法 customMethod，可以这样做：
  // customMethod(): void;
  config(key: string, value: any);
  // 通过实例获取当前编辑器的值
  getMarkdown(): string;       // 获取 Markdown 源码
  getHTML(): string;           // 获取 Textarea 保存的 HTML 源码
  getPreviewedHTML();
  setTheme(themeName: string): void;
  setEditorTheme(themeName: string): void;
  setPreviewTheme(themeName: string): void;
  setMarkdown(content: string): void;
}

interface LangToolBar {
  [key: string]: string;
}

interface EditorMdLang {
  name?: string, // "zh-cn"
  description?: string, //"开源在线Markdown编辑器<br/>Open source online Markdown editor."
  tocTitle?: string, // "目录"
  toolbar?: LangToolBar,
  button?: {
      //...
  },
  dialog?: {
      //...
  }
}

interface ToolbarIconsClass {
  [key: string]: string;
}

interface ToolbarCustomIcons {
  [key: string]: string;
  // lowercase: string; "<a href=\"javascript:;\" title=\"Lowercase\" unselectable=\"on\"><i class=\"fa\" name=\"lowercase\" style=\"font-size:24px;margin-top: -10px;\">a</i></a>",
  // "ucwords": string; "<a href=\"javascript:;\" title=\"ucwords\" unselectable=\"on\"><i class=\"fa\" name=\"ucwords\" style=\"font-size:20px;margin-top: -3px;\">Aa</i></a>"
}
/**
* @param {any}      cm         CodeMirror对象
* @param {any}      icon       图标按钮jQuery元素对象
* @param {any}      cursor     CodeMirror的光标对象，可获取光标所在行和位置
* @param {String}      selection  编辑器选中的文本
*/
type ToolHandlesFunc = (cm?: any, icon?: any, cursor?: any, selection?: String) => any

interface ToolbarHandlers {
  // ucwords?: function() : ToolbarHandlers.ucwords;
  // lowercase?: function() : ToolbarHandlers.lowercase;
  
  [key: string] : ToolHandlesFunc;
}

interface ToolbarTitles {
  [key: string]: string;
}
interface ToolbarIconTexts {
  [key: string]: string;
}

interface EditorMdOptions {
  id?: string;
  mode?: string; // gfm or markdown
  name?: string; // Form element name for post
  value?: string; // value for CodeMirror, if mode not gfm/markdown
  theme?: string; // Editor.md self themes, before v1.5.0 is CodeMirror theme, default empty
  editorTheme?: string; // "default"Editor area, this is CodeMirror theme at v1.5.0
  previewTheme?: string; // Preview area theme, default empty
  markdown?: string; // Markdown source code
  appendMarkdown?: string; // if in init textarea value not empty, append markdown to textarea
  width?: string; // "100%"
  height?: string; // "100%"
  path?: string; // Dependents module file directory "./lib/"
  pluginPath?: string; // If this empty, default use settings.path + "../plugins/"
  delay?: number; // Delay parse markdown to html, Uint : ms
  autoLoadModules?: boolean; // Automatic load dependent module files boolean
  watch?: boolean; // boolean
  placeholder?: string; // "Enjoy Markdown! coding now..."
  gotoLine?: boolean; // Enable / disable goto a line, boolean
  codeFold?: boolean;
  autoHeight?: boolean;
  autoFocus?: boolean; // Enable / disable auto focus editor left input area, boolean
  autoCloseTags?: boolean; // boolean
  searchReplace?: boolean; // Enable / disable (CodeMirror) search and replace function, boolean
  syncScrolling?: boolean | 'single'; // options: boolean | boolean | "single", default boolean
  readOnly?: boolean; // Enable / disable readonly mode
  tabSize?: number;
  indentUnit?: number;
  lineNumbers?: boolean; // Display editor line numbers
  lineWrapping?: boolean;
  autoCloseBrackets?: boolean;
  showTrailingSpace?: boolean;
  matchBrackets?: boolean;
  indentWithTabs?: boolean;
  styleSelectedText?: boolean;
  matchWordHighlight?: boolean; // options: boolean, boolean, "onselected"
  styleActiveLine?: boolean; // Highlight the current line
  dialogLockScreen?: boolean;
  dialogShowMask?: boolean;
  dialogDraggable?: boolean;
  dialogMaskBgColor?: string; // "#fff"
  dialogMaskOpacity?: number; // 0.1
  fontSize?: string; // "13px"
  saveHTMLToTextarea?: boolean; // If enable, Editor will create a <textarea name="{editor-id}-html-code"> tag save HTML code for form post to server-side.
  disabledKeyMaps?: [];

  onload?: function(): void | null;
  onresize?: function(): void | null;
  onchange?: function(): void | null;
  onwatch?: function(): void | null;
  onunwatch?: function(): void | null;
  onpreviewing?: function(): void | null;
  onpreviewed?: function(): void | null;
  onfullscreen?: function(): void | null;
  onfullscreenExit?: function(): void | null;
  onscroll?: function(): void | null;
  onpreviewscroll?: function(): void | null;

  imageUpload?: boolean; // Enable/disable upload
  imageFormats?: string[]; // ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
  imageUploadURL?: string; // Upload url
  crossDomainUpload?: boolean; // Enable/disable Cross-domain upload
  uploadCallbackURL?: string; // Cross-domain upload callback url

  toc?: boolean; // Table of contents
  tocm?: boolean; // Using [TOCM], auto create ToC dropdown menu
  tocTitle?: string; // for ToC dropdown menu button
  tocDropdown?: boolean; // Enable/disable Table Of Contents dropdown menu
  tocContainer?: string; // Custom Table Of Contents Container Selector
  tocStartLevel?: number; // Said from H1 to create ToC, 1
  htmlDecode?: boolean; // Open the HTML tag identification
  pageBreak?: boolean; // Enable parse page break [========]
  atLink?: boolean; // for @link
  emailLink?: boolean; // for email address auto link
  taskList?: boolean; // Enable Github Flavored Markdown task lists
  emoji?: boolean; // :emoji: , Support Github emoji, Twitter Emoji (Twemoji);
  // Support FontAwesome icon emoji :fa-xxx: > Using fontAwesome icon web fonts;
  // Support Editor.md logo icon emoji :editormd-logo: :editormd-logo-1x: > 1~8x;
  tex?: boolean; // TeX(LaTeX), based on KaTeX
  flowChart?: boolean; // flowChart.js only support IE9+
  sequenceDiagram?: boolean; // sequenceDiagram.js only support IE9+
  previewCodeHighlight?: boolean; // Enable / disable code highlight of editor preview area

  toolbar?: boolean; // show or hide toolbar
  toolbarAutoFixed?: boolean; // on window scroll auto fixed position
  toolbarIcons?: string | function (): string[]; // [full]Toolbar icons mode, options: full, simple, mini, See `editormd.toolbarModes` property.
  toolbarTitles?: ToolbarTitles;
  toolbarHandlers?: ToolbarHandlers;
  toolbarCustomIcons?: ToolbarCustomIcons;
  toolbarIconTexts?: ToolbarIconTexts;
  toolbarIconsClass?: ToolbarIconsClass;

  lang?: EditorMdLang;
}
