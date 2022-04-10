# docker
# Redis
- Access to redis docker
`docker-compose exec redis bash`
- Access to redis server via command line
`redis-cli`
- Set key-value on redis server on docker
`set "o:laravel_cache:test4" 's:17:"test4 from docker";'`
```
o:laravel_cache: => prefix on laravel config
17 => strlen of 'test4 from docker' string
;  => end of value
```