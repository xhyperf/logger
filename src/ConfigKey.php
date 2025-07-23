<?php

namespace xhyperf\LoggerPlus;

class ConfigKey
{
    /**
     * 输出类型
     */
    const string OUTPUT_TYPE = 'logger.output.type';

    /**
     * 是否收集响应正文数据
     */
    const string RESPONSE_ENABLE = 'logger.gather.response.enable';

    /**
     * 是否记录SQL语句的trace信息，生产环境不建议开启
     */
    const string SQL_TRACE_ENABLE = 'logger.gather.sql.trace.enable';
}