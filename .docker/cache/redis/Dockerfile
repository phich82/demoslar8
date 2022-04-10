FROM redis:6.2.6

RUN apt-get update && apt-get -y install vim procps

# COPY conf/redis.conf /usr/local/etc/redis/redis.conf

EXPOSE 6379

# Use a custom redis-server command with --save 20 1 which instructs
# the server to save 1 or more writes every 20 seconds to disk in case
# the server restarts. We are using the --requirepass parameter to add
# authentication with the password to read/write data on the Redis server
# CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
# CMD ["redis-server", "--save", "20 1", "--loglevel", "warning", "--requirepass", "123@haPHAT"]
