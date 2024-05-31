# 영화진흥회 Open API를 사용하여 Python으로 mariaDB에 값 삽입과 이를 통한 php 인터페이스 구현 
Inserting values ​​into DB using Python using Korea Film Council Open API and implementing PHP server through this
---
## step 1) DB설계 및 구축.


ERD


![image](https://github.com/savom/php-db-python/assets/56549021/aab3b4d3-53ca-40f5-b512-a51d5e1d7403)


Database_GuideLine_for_mariaDB.pptx 파일을 참고하여 DB를 설계

## step 2) Python을 통한 Open API 값과 DB 테이블에 값 삽입.


example)python_to_DB.py 파일을 사용하여 step 1에서 만들어 놓은 DB table에 Open API에서 받아온 값을 삽입하는 코드와 Python과 mariaDB의 연결
<pre><code>
  import time
  import requests
  import mysql.connector
  import pymysql

  # API 키와 URL 설정
  MOVIE_API_KEY = '__your_key__'
  BOXOFFICE_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/boxoffice/searchDailyBoxOfficeList.json?key={MOVIE_API_KEY}&targetDt='
  MOVIE_INFO_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/movie/searchMovieInfo.json?key={MOVIE_API_KEY}&movieCd='

  def fetch_movie_info(movie_code):
      try:
          response = requests.get(MOVIE_INFO_API_URL + movie_code)
          response.raise_for_status()
          movie_info_data = response.json()['movieInfoResult']['movieInfo']

          movie_info = {
              'movieCd': movie_code,
              'directors': [director['peopleNm'] for director in movie_info_data['directors']],
              'actors': [actor['peopleNm'] for actor in movie_info_data['actors']],
              'genres': [genre['genreNm'] for genre in movie_info_data['genres']],
              'openDt': movie_info_data['openDt'] if 'openDt' in movie_info_data else None,
              'showTm': movie_info_data['showTm'] if 'showTm' in movie_info_data else None,
              'watchGradeNm': movie_info_data['audits'][0]['watchGradeNm'] if movie_info_data['audits'] else None
          }

          return movie_info
      except Exception as e:
          print(f"An error occurred while fetching movie info for {movie_code}: {e}")
          return None

  def fetch_boxoffice_data():
      try:
          yesterday = time.strftime('%Y%m%d', time.localtime(time.time() - 86400))  # 어제 날짜로 데이터 가져오기
          response = requests.get(BOXOFFICE_API_URL + yesterday)
          response.raise_for_status()
          boxoffice_data = response.json()['boxOfficeResult']['dailyBoxOfficeList']

          detailed_boxoffice_data = []
          for movie in boxoffice_data:
              movie_code = movie['movieCd']
              movie_info = fetch_movie_info(movie_code)
              if movie_info:
                  detailed_boxoffice_data.append({
                      'rank': movie['rank'],
                      'movieCd': movie_code,
                      'movieNm': movie['movieNm'],
                      'openDt': movie_info['openDt'],
                      'showTm': movie_info['showTm'],
                      'watchGradeNm': movie_info['watchGradeNm'],
                      'directors': ', '.join(movie_info['directors']),
                      'actors': ', '.join(movie_info['actors']),
                      'genres': ', '.join(movie_info['genres']),
                  })

          return detailed_boxoffice_data
      except Exception as e:
          print(f"An error occurred while fetching box office data: {e}")
          return []

  def save_to_database(data):
      try:
          conn = mysql.connector.connect(
              host='__your_host'__,
              port=__your_port__,
              user='__your_id__',
              password='__your_pw__',
              database='__your_db_name__'
          )
          cursor = conn.cursor()

          for movie in data:
              cursor.execute('''
                  INSERT INTO movie (
                      movie_num, movie_title, movie_character, movie_genre, movie_Date, movie_running, movie_age
                  ) VALUES (%s, %s, %s, %s, %s, %s, %s)
                  ON DUPLICATE KEY UPDATE
                      movie_title=VALUES(movie_title), movie_character=VALUES(movie_character), movie_genre=VALUES(movie_genre),
                      movie_Date=VALUES(movie_Date), movie_running=VALUES(movie_running), movie_age=VALUES(movie_age)
                    
              ''', (
                  movie['movieCd'], movie['movieNm'], movie['directors'] + ', ' + movie['actors'], movie['genres'], 
                  movie['openDt'], movie['showTm'], movie['watchGradeNm']
              ))

          conn.commit()
          conn.close()
      except Exception as e:
          print(f"An error occurred while saving data to the database: {e}")

  def update_data_periodically():
      while True:
          boxoffice_data = fetch_boxoffice_data()
          if boxoffice_data:
              save_to_database(boxoffice_data)
          time.sleep(43200)  # 12시간마다 데이터 업데이트

  if __name__ == '__main__':
      update_data_periodically()
</code></pre>

볼드체 된 부분과 open api에서 받아온 키값만 제대로 입력하면 된다.

## step 3) php 구현
