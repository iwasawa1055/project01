/*!
 * airAutoKana v1.0.0
 *
 * Require:		jquery
 * Copyright:	2016, AiR&D Inc. (http://a-i-r-d.co.jp/)
 * Author:		Kyow
 * Licensed:	Under the MIT License
 * ChangeLog:   2017/01/05 fix cross browser problem(murai)
 */

(function ($)
{
    $.fn.airAutoKana = function (_options)
    {
        //* Default Options + Argument Options
        var options = $.extend(
                {
                    dest: false,
                    katakana: false,
                }, _options);

        //* Validation
        if (!options['dest']) {
            console.error("ArgumentsError: Options Argument's 'dest' selector is invalid.");
            return false;
        }

        //* Object Config
        var __this = $(this);
        var __dest_obj = $(options['dest']);
        if ($('#airAutoKana').length === 0) {
            $('body').append('<input id="airAutoKana" type="hidden">');
        }
        var __prev_obj = $('#airAutoKana');

        //* Regex Config
        var __hiragana = '\\u3041-\\u3096\\u309D\\u309E';
        var __regex_hiragana = new RegExp('[' + __hiragana + ']', 'g');
        var __regex_not_hiragana = new RegExp('[^' + __hiragana + ']', 'g');
        var __katakana = '\\u30A1-\\u30F6\\u30FD\\u30FE';
        var __regex_katakana = new RegExp('[' + __katakana + ']', 'g');
        var __regex_not_katakana = new RegExp('[^' + __katakana + ']', 'g');
        var __kanamark = '\\u3000\\u30FB\\u30FC';
        var __regex_kanamark = new RegExp('[' + __kanamark + ']', 'g');
        var __regex_not_kanamark = new RegExp('[^' + __kanamark + ']', 'g');
        var __fw_ascii = '\\u0021-\\u007E\\uFF01-\\uFF5E';
        var __regex_fw_ascii = new RegExp('[' + __fw_ascii + ']', 'ig');
        var __regex_not_fw_ascii = new RegExp('[^' + __fw_ascii + ']', 'ig');

        //* Others Config
        var __timer = null;

        //* Method
        var App =
        {
            checkSourceElement: function ()
            {
                //* Focus Status Get
                var flag = __this.is(':focus');

                try {
                    //* Now & Prev Value Get
                    var now_val = __this.val();
                    var prev_val = __prev_obj.val();

                    //* Abort: No Focus
                    if (!flag) {
                        throw 'Abort';
                    }

                    //* Abort: Init
                    if (now_val === '') {
                        $(__dest_obj).val('');
                        $(__prev_obj).val('');
                        throw 'Abort';
                    }

                    //* Aborg: Same
                    if (prev_val === now_val) {
                        throw 'Abort';
                    }
                    // モバイルキーボード入力対策処理
                    // 前回と文字数同じかつ最後の文字が両方ともひらがなorカタカナなら
                    // 読みの最後の文字を置き換え
                    if (now_val.length === prev_val.length) {
                        var now_val_tail = now_val.substr(now_val.length - 1);
                        var prev_val_tail = prev_val.substr(prev_val.length - 1);

                        if ((now_val_tail.match(__regex_hiragana) && prev_val_tail.match(__regex_hiragana))
                         || (now_val_tail.match(__regex_katakana) && prev_val_tail.match(__regex_katakana))
                        ) {
                            //** To Katakana
                            var diff_tail_val = '';
                            if (options['katakana'] === true) {
                                diff_tail_val = now_val_tail.replace(__regex_hiragana, function (match)
                                {
                                    var char_code = match.charCodeAt(0) + 0x60;
                                    return String.fromCharCode(char_code);
                                });
                                //** To Hiragana
                            } else {
                                diff_tail_val = now_val_tail.replace(__regex_katakana, function (match)
                                {
                                    var char_code = match.charCodeAt(0) - 0x60;
                                    return String.fromCharCode(char_code);
                                });
                            }
                            var dest_val = __dest_obj.val();
                            dest_val = dest_val.substr(0, dest_val.length - 1);
                            __dest_obj.val(dest_val + diff_tail_val);
                            throw 'dest change and Abort';
                        }
                    }

                    // Now & Prev Value to Kana
                    // カタカナはひらがなに変換して比較
                    // (入力途中でしれっと入力内容がカタカナモードになる場合対策)
                    now_kana = now_val.replace(__regex_fw_ascii, '');
                    now_kana = now_kana.replace(__regex_katakana, function(match)
                    {
                        var char_code = match.charCodeAt(0) - 0x60;
                        return String.fromCharCode(char_code);
                    });
                    prev_kana = prev_val.replace(__regex_fw_ascii, '');
                    prev_kana = prev_kana.replace(__regex_katakana, function(match)
                    {
                        var char_code = match.charCodeAt(0) - 0x60;
                        return String.fromCharCode(char_code);
                    });

                    // Abort: Now Value Deletion
                    if (now_kana.length < prev_kana.length) {
                        throw 'Abort';
                    }

                    //* Diff Value Get From Now Kana VS Pref Kana
                    var regex_prev_kana = new RegExp('^' + prev_kana);
                    var diff_val = now_kana.replace(regex_prev_kana, '');

                    if (now_kana === diff_val && prev_kana !== '') {
                        throw 'Abort';
                    }

                    //* Hw Space to Fw Space
                    diff_val = diff_val.replace(' ', '　');

                    //* Diff Value Appanding To Object Element
                    // Hiragana or Katakana or Kanamark
                    if (!diff_val.match(__regex_not_hiragana) || !diff_val.match(__regex_not_katakana) || !diff_val.match(__regex_not_kanamark)) {

                        //** To Katakana
                        if (options['katakana'] === true) {
                            diff_val = diff_val.replace(__regex_hiragana, function (match)
                            {
                                var char_code = match.charCodeAt(0) + 0x60;
                                return String.fromCharCode(char_code);
                            });
                            //** To Hiragana
                        } else {
                            diff_val = diff_val.replace(__regex_katakana, function (match)
                            {
                                var char_code = match.charCodeAt(0) - 0x60;
                                return String.fromCharCode(char_code);
                            });
                        }

                        // Diff Value to Dest Obj
                        var dest_val = __dest_obj.val();
                        __dest_obj.val(dest_val + diff_val);
                    }

                } catch (e) {

                } finally {
                    __prev_obj.val(now_val);
                    __timer = setTimeout(App.checkSourceElement, 50);
                }
            },
        };

        //* Focus
        $(this).on('focus', function ()
        {
            App.checkSourceElement();
        });

        //* Blur
        $(this).on('blur', function ()
        {
            clearTimeout(__timer);
        });

        return this;
    };

})(jQuery);

