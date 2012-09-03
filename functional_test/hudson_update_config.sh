#!/bin/bash

sed -i -e "s#\"HUD_PARAM_TEST_HOST_ARRAY\"#$HUD_PARAM_TEST_HOST_ARRAY#" \
    -e "s#\"HUD_PARAM_CLOUD_NAME\"#$HUD_PARAM_CLOUD_NAME#" \
    -e "s#\"HUD_PARAM_MEMBASE_BUILD\"#$HUD_PARAM_MEMBASE_BUILD#" \
	-e "s#\"HUD_PARAM_PROXYSERVER_BUILD\"#$HUD_PARAM_PROXYSERVER_BUILD#" \
	-e "s#\"HUD_PARAM_BACKUP_TOOL_BUILD\"#$HUD_PARAM_BACKUP_TOOL_BUILD#" \
    -e "s#\"HUD_PARAM_PECL_BUILD\"#$HUD_PARAM_PECL_BUILD#" \
    -e "s#\"/tmp/results\"#'/tmp/$JOB_NAME'#" \
    -e "s#\"HUD_PARAM_TEST_SUITE_ARRAY\"#$HUD_PARAM_TEST_SUITE_ARRAY#" config.php