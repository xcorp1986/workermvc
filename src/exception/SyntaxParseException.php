<?php
/**
 * Created by lobtao.
 */

namespace workermvc\exception;


class SyntaxParseException extends HttpException
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * SyntaxParseException constructor.
     *
     * @param string $filepath
     * @param string $message
     */
    public function __construct($filepath, $message = "")
    {
        $message = "Syntax Parse Error: ".$filepath."\n".$message;
        parent::__construct(500, $message, true);
        $this->filepath = $filepath;
        if(config("think.debug")==true) {
            $this->setHttpBody($this->getDebugHttpBody());
        }else{
            $this->setHttpBody($this->getProHttpBody());
        }
    }

    /**
     * Get Http Return in Debug Mode
     *
     * @return string
     */
    private function getDebugHttpBody(){
        return $this->loadTemplate("TracingPage", [
            'title' => think_core_lang("tracing page syntax parse error"),
            'main_msg' => think_core_lang("tracing page syntax parse error"),
            'main_msg_detail' => think_core_shorten_filepath($this->filepath),
            'main_error_pos' => fix_slashes_in_path($this->filepath).":",
            'main_error_detail' => think_core_lang("tracing page syntax parse error detail").": <br>".$this->getMessage(),
            'lang_tracing' => think_core_lang("tracing page tracing"),
            'lang_src' => think_core_lang("tracing page src file"),
            'lang_line' => think_core_lang('tracing page line num'),
            'lang_call' => think_core_lang("tracing page call"),
            'tracing_table' => $this->formTracingTable(),
            'request_table' => $this->formRequestTable(),
            'env_table' => $this->formEnvTable(),
            'lang_key' => think_core_lang("tracing page key"),
            'lang_value' => think_core_lang("tracing page value"),
            'lang_request' => think_core_lang("tracing page request detail"),
            'lang_env' => think_core_lang("tracing page env")
        ]);
    }

    /**
     * Get Http Return in Production Mode
     *
     * @return string
     */
    private function getProHttpBody(){
        return $this->loadTemplate("ErrorPage", [
            'title'=>think_core_lang('page error title'),
            'code'=>500,
            'msg'=>think_core_lang('page error msg')
        ]);
    }
}